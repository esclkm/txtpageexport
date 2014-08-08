<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=tools
 * [END_COT_EXT]
 */
/**
 * plugin txtpageexport for Cotonti Siena
 * 
 * @package txtpageexport
 * @version 1.0.0
 * @author esclkm
 * @copyright 
 * @license BSD
 *  */
// Generated by Cotonti developer tool (littledev.ru)
defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('txtpageexport', 'plug');

$mskin = cot_tplfile(array('txtpageexport', 'tools'), 'plug');
$t = new XTemplate($mskin);
foreach (glob($cfg['plugins_dir']."/txtpageexport/tpl/txtpageexport.*.tpl") as $filename)
{
	$filename = str_replace($cfg['plugins_dir']."/txtpageexport/tpl/txtpageexport.", "", $filename);
	$filename = htmlspecialchars(str_replace(".tpl", "", $filename));
	$t->assign(array(
		"EXP_GENERATE_XML" => $filename,
		"EXP_GENERATE_XML_URL" => cot_url('plug', 'r=txtpageexport&file='.$filename),
	));
	if (!empty($cfg['plugin']['txtpageexport']['where2']) && $cfg['plugin']['txtpageexport']['where1'] != $cfg['plugin']['txtpageexport']['where2'])
	{
		$t->assign('EXP_GENERATE_XML_URL_VAR2', cot_url('plug', 'r=txtpageexport&where=2&file='.$filename));
	}
	if (!empty($cfg['plugin']['txtpageexport']['where3']) && $cfg['plugin']['txtpageexport']['where1'] != $cfg['plugin']['txtpageexport']['where3']
		&& ($cfg['plugin']['txtpageexport']['where3'] != $cfg['plugin']['txtpageexport']['where2']))
	{
		$t->assign('EXP_GENERATE_XML_URL_VAR3', cot_url('plug', 'r=txtpageexport&where=3&file='.$filename));
	}
	$t->parse('MAIN.ROW');
}
$t->assign(array(
	"EXP_GENERATE_XML" => cot_url('plug', 'r=txtpageexport'),
));

$t->parse('MAIN');
if (COT_AJAX)
{
	$t->out('MAIN');
}
else
{
	$adminmain = $t->text('MAIN');
}
?>