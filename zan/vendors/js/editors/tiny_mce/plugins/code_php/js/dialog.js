tinyMCEPopup.requireLangPack();

var ExampleDialog = {
	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');
	},

	insert : function() {
		// Insert the contents from the input into the document
		var php = document.forms[0].someval.value;
		
		php = php.replace(/<\?php/g, '<span style="color: #FF0000">&lt;?php</span>');
		php = php.replace(/\?>/g, '<span style="color: #FF0000">?&gt;</span>');
				
		php = php.replace(/\$_POST/g, '<span style="color: #17abf0">$_POST</span>');
		php = php.replace(/\$_GET/g, '<span style="color: #17abf0">$_GET</span>');	
		php = php.replace(/\$_SERVER/g, '<span style="color: #17abf0">$_SERVER</span>');
		php = php.replace(/\$_SESSION/g, '<span style="color: #17abf0">$_SESSION</span>');
		php = php.replace(/\$_COOKIE/g, '<span style="color: #17abf0">$_COOKIE</span>');
		
		php = php.replace(/if/g, '<span style="color: #006600">if</span>');
		php = php.replace(/return/g, '<span style="color: #006600">return</span>');
		php = php.replace(/TRUE/g, '<span style="color: #006600">TRUE</span>');
		php = php.replace(/true/g, '<span style="color: #006600">true</span>');
		php = php.replace(/FALSE/g, '<span style="color: #006600">FALSE</span>');
		php = php.replace(/false/g, '<span style="color: #006600">false</span>');
		php = php.replace(/null/g, '<span style="color: #006600">null</span>');
		php = php.replace(/NULL/g, '<span style="color: #006600">NULL</span>');
		php = php.replace(/class/g, '<span style="color: #006600">class</span>');
		php = php.replace(/extends/g, '<span style="color: #006600">extends</span>');
		php = php.replace(/die/g, '<span style="color: #006600">die</span>');
		php = php.replace(/private/g, '<span style="color: #006600">private</span>');
		php = php.replace(/public/g, '<span style="color: #006600">public</span>');
		php = php.replace(/static/g, '<span style="color: #006600">static</span>');
		php = php.replace(/foreach/g, '<span style="color: #006600">foreach</span>');
		php = php.replace(/for/g, '<span style="color: #006600">for</span>');
		php = php.replace(/while/g, '<span style="color: #006600">while</span>');
		php = php.replace(/elseif/g, '<span style="color: #006600">elseif</span>');
		php = php.replace(/global/g, '<span style="color: #006600">global</span>');
		php = php.replace(/else/g, '<span style="color: #006600">else</span>');
		php = php.replace(/include_once/g, '<span style="color: #006600">include_once</span>');
		php = php.replace(/include/g, '<span style="color: #006600">include_once</span>');
		php = php.replace(/require/g, '<span style="color: #006600">require</span>');
		php = php.replace(/require_once/g, '<span style="color: #006600">require_once</span>');
		php = php.replace(/print/g, '<span style="color: #006600">print</span>');
		
		php = php.replace(/function/g, '<span style="color: #007eff">function</span>');
		php = php.replace(/preg_replace/g, '<span style="color: #007eff">preg_replace</span>');
		php = php.replace(/str_replace/g, '<span style="color: #007eff">str_replace</span>');
		php = php.replace(/ and /g, '<span style="color: #007eff"> and </span>');
		php = php.replace(/htmlentities/g, '<span style="color: #007eff">htmlentities</span>');
		php = php.replace(/count/g, '<span style="color: #007eff">count</span>');
		php = php.replace(/utf8_encode/g, '<span style="color: #007eff">utf8_encode</span>');
		php = php.replace(/utf8_decode/g, '<span style="color: #007eff">utf8_decode</span>');
		php = php.replace(/substr/g, '<span style="color: #007eff">substr</span>');
		php = php.replace(/defined/g, '<span style="color: #007eff">defined</span>');
		php = php.replace(/\$this->/g, '<span style="color: #007eff">$this-&gt;</span>');
		php = php.replace(/is_array/g, '<span style="color: #007eff">is_array</span>');
		php = php.replace(/array_keys/g, '<span style="color: #007eff">array_keys</span>');
		php = php.replace(/sizeof/g, '<span style="color: #007eff">sizeof</span>');
		php = php.replace(/ini_set/g, '<span style="color: #007eff">ini_set</span>');
		php = php.replace(/strtolower/g, '<span style="color: #007eff">strtolower</span>');
		php = php.replace(/explode/g, '<span style="color: #007eff">explode</span>');
		php = php.replace(/move_uploaded_file/g, '<span style="color: #007eff">move_uploaded_file</span>');
		php = php.replace(/mkdir/g, '<span style="color: #007eff">mkdir</span>');
		php = php.replace(/unlink/g, '<span style="color: #007eff">unlink</span>');
		php = php.replace(/ or /g, '<span style="color: #007eff"> or </span>');
		php = php.replace(/file_exists/g, '<span style="color: #007eff">file_exists</span>');
		php = php.replace(/strlen/g, '<span style="color: #007eff">strlen</span>');
		php = php.replace(/substr_count/g, '<span style="color: #007eff">substr_count</span>');
		php = php.replace(/strstr/g, '<span style="color: #007eff">strstr</span>');
		php = php.replace(/html_entity_decode/g, '<span style="color: #007eff">html_entity_decode</span>');
		php = php.replace(/substr_count/g, '<span style="color: #007eff">substr_count</span>');
		php = php.replace(/mail/g, '<span style="color: #007eff">mail</span>');
		php = php.replace(/func_num_args/g, '<span style="color: #007eff">func_num_args</span>');
		php = php.replace(/func_get_args/g, '<span style="color: #007eff">func_get_args</span>');
		php = php.replace(/header/g, '<span style="color: #007eff">header</span>');
		php = php.replace(/is_null/g, '<span style="color: #007eff">is_null</span>');
		php = php.replace(/mysqli_query/g, '<span style="color: #007eff">mysqli_query</span>');
		php = php.replace(/mysqli_multi_query/g, '<span style="color: #007eff">mysqli_multi_query</span>');
		php = php.replace(/mysqli_next_result/g, '<span style="color: #007eff">mysqli_next_result</span>');
		php = php.replace(/mysqli_more_results/g, '<span style="color: #007eff">mysqli_more_results</span>');
		php = php.replace(/mysqli_store_result/g, '<span style="color: #007eff">mysqli_store_result</span>');
		php = php.replace(/stripos/g, '<span style="color: #007eff">stripos</span>');
		php = php.replace(/stristr/g, '<span style="color: #007eff">stristr</span>');
		php = php.replace(/isset/g, '<span style="color: #007eff">isset</span>');
		php = php.replace(/empty/g, '<span style="color: #007eff">empty</span>');
		php = php.replace(/unset/g, '<span style="color: #007eff">unset</span>');
		php = php.replace(/===/g, '<span style="color: #007eff">===</span>');
		php = php.replace(/==/g, '<span style="color: #007eff">==</span>');
		php = php.replace(/<=/g, '<span style="color: #007eff">&lt;=</span>');
		php = php.replace(/>=/g, '<span style="color: #007eff">&gt;=</span>');
		php = php.replace(/\+\+/g, '<span style="color: #007eff">++</span>');
		php = php.replace(/basename/g, '<span style="color: #007eff">basename</span>');
		php = php.replace(/array_shift/g, '<span style="color: #007eff">array_shift</span>');
		php = php.replace(/array_diff/g, '<span style="color: #007eff">array_diff</span>');
		
		php = php.replace(/\[0\]/g, '[<span style="color: #FF0000">0</span>]');
		php = php.replace(/\[1\]/g, '[<span style="color: #FF0000">1</span>]');
		php = php.replace(/\[2\]/g, '[<span style="color: #FF0000">2</span>]');
		php = php.replace(/\[3\]/g, '[<span style="color: #FF0000">3</span>]');
		php = php.replace(/\[4\]/g, '[<span style="color: #FF0000">4</span>]');
		php = php.replace(/\[5\]/g, '[<span style="color: #FF0000">5</span>]');
		php = php.replace(/\[6\]/g, '[<span style="color: #FF0000">6</span>]');
		php = php.replace(/\[7\]/g, '[<span style="color: #FF0000">7</span>]');
		php = php.replace(/\[8\]/g, '[<span style="color: #FF0000">8</span>]');
		php = php.replace(/\[9\]/g, '[<span style="color: #FF0000">9</span>]');
		
		var code = '<pre style=\"width: 95%; height: 300px; margin: 0 auto; padding: 10px; overflow: auto; border: 1px solid #333; background-color: #F5F5F5;\" class=\"code\">' + php + '<' + '/pre>';
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, code);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(ExampleDialog.init, ExampleDialog);
