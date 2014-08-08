<?php
/* 
 * [BEGIN_COT_EXT]
 * Hooks=ajax
 * [END_COT_EXT]
 * */

/**
 * plugin txtpageexport for Cotonti Siena
 * 
 * @package txtpageexport
 * @version 1.0.0
 * @author esclkm
 * @copyright 
 * @license BSD
 *  */

defined('COT_CODE') or die('Wrong URL');


$file = cot_import('file','G','TXT');
if (!empty($file) && cot_auth('plug', 'txtpageexport', 'A'))
{
	require_once cot_incfile('page', 'module');
	
	$wheretype = cot_import('where','G','INT');
	$wheretype = (in_array($wheretype, array(1, 2, 3))) ? $wheretype : 1;

	$exfile = explode('.', $file);

	$mskin = cot_tplfile(array('txtpageexport', $file), 'plug');
	$file = $exfile[0];
	$extf = (!$exfile[1]) ? 'txt' : $exfile[1];
	
	$t1 = new XTemplate($mskin);
	if(!empty($cfg['plugin']['txtpageexport']['cat']))
	{
		$excat = cot_structure_children('page', $cfg['plugin']['txtpageexport']['cat'], true, true, false);
		$where['cat'] = (count($excat)) ? "page_cat IN ('". implode("', '", $excat) ."')" : '';
	}
	$where['perm'] = "(page_state=0 OR page_state=2)";
	$where['where'] = $cfg['plugin']['txtpageexport']['where'.$wheretype];
	$where = array_filter($where);
	$where = implode(" AND ", $where);
	$sql2 = $db->query("SELECT * FROM $db_pages WHERE  $where");
	$jj = 0;
	while($row2 = $sql2->fetch())
	{
		$jj++;

		$row2['page_text'] = str_replace( array( "\n", "\r", "\t" ), '', $row2['page_text']);
		$row2['page_desc'] = str_replace( array( "\n", "\r", "\t" ), '', $row2['page_desc']);

		$row2['page_text'] = (str_replace( "\"", '&quot;', $row2['page_text']));
		$row2['page_desc'] = (str_replace( "\"", '&quot;', $row2['page_desc']));
		
		$textpag = strip_tags(cot_parse($row2['page_text'], $cfg['page']['markup'], $row2['page_parser']));	

		$t1->assign(cot_generate_pagetags($row2, 'EXP_ROW_', '', false, false, '', false));
		$t1->assign(array(
			'EXP_ROW_NOHTML' => htmlspecialchars_decode($textpag),
			'EXP_ROW_NOHTMLDESC' => htmlspecialchars_decode(strip_tags($row2['page_desc'])),
			'EXP_ROW_ODDEVEN' => cot_build_oddeven($jj),
			'EXP_ROW_NUM' => $jj,
			'SEP' => '[[br]]',
		));/**/
			
		$t1->parse('MAIN.ROW');
	}

	$t1->parse('MAIN');
	$xtext = $t1->text('MAIN');
//	$xtext = iconv("UTF-8","WINDOWS-1251//IGNORE",$xtext);
	if(!empty($cfg['plugin']['txtpageexport']['replacefrom']) && !empty($cfg['plugin']['txtpageexport']['replaceto']))
	{
		$umlaut_array = explode(",", $cfg['plugin']['txtpageexport']['replacefrom']);
		$umlaut_replace   = explode(",", $cfg['plugin']['txtpageexport']['replaceto']);
		$xtext = str_replace($umlaut_array, $umlaut_replace, $xtext);
	}
	
	if(!empty($cfg['plugin']['txtpageexport']['encoding']))
	{	
		$xtext = mb_convert_encoding($xtext, $cfg['plugin']['txtpageexport']['encoding']);
	}
	
	$xtext = str_replace('[[br]]', "\r\n" , $xtext);
	file_put_contents($cfg['plugin']['txtpageexport']['folder'].'/'.$file.'.'.$extf, $xtext);

	if(file_exists($cfg['plugin']['txtpageexport']['folder'].'/'.$file.'.'.$extf))
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public", false);
		header("Content-Description: File Transfer");
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment;filename="'.$file.date("_Ymd-Hi").'.'.$extf.'"');
		header("Content-Transfer-Encoding: binary");
		header('Content-Length: ' . filesize($cfg['plugin']['txtpageexport']['folder'].'/'.$file.'.'.$extf));
		ob_clean();
		flush();
		readfile($cfg['plugin']['txtpageexport']['folder'].'/'.$file.'.'.$extf);
		exit;
	}
}
?>