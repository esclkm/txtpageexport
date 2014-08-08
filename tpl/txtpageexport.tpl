<!-- BEGIN: MAIN -->

		<div class="block">
			<h2>{PHP.L.export}</h2>
			<ul class="follow">
			<!-- BEGIN: ROW -->
			<li><a href="{EXP_GENERATE_XML_URL}">{EXP_GENERATE_XML}</a> 
				<!-- IF {EXP_GENERATE_XML_URL_VAR2} -->, <a href="{EXP_GENERATE_XML_URL_VAR2}">2</a> <!-- ENDIF -->
				<!-- IF {EXP_GENERATE_XML_URL_VAR3} -->, <a href="{EXP_GENERATE_XML_URL_VAR3}">3</a> <!-- ENDIF -->
			</li>
			<!-- END: ROW -->
			</ul>
			<a class="button" href="admin.php?m=config&n=edit&o=plug&p=txtpageexport" title="{PHP.L.Configuration}">{PHP.L.Configuration}</a>
		</div>

<!-- END: MAIN -->