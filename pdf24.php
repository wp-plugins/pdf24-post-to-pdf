<?php

/**********  SETTINGS ******************************/

$pdf24StyleForm 	= "text-align:center; border: 1px solid silver; padding: 5px;";
$pdf24StyleInput 	= "";
$pdf24StyleButton 	= "";

/********** END SETTINGS ******************************/

$pdf24ScriptUrl 	= "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";
	
//zurücksetzen
rewind_posts();	
$pdf24PostsArr = array();

//pdf24 filter deaktivieren
remove_filter("the_content","pdf24Plugin_content");

if (have_posts()) {

	while (have_posts()) {
		the_post();
		
		//filter auf content anwenden
		$content = get_the_content();
		$content =  apply_filters('the_content', $content);

		$pdf24Params = array(		
			"postTitle" => get_the_title(),
			"postLink" => get_permalink(),
			"postAuthor" => get_the_author(),
			"postDateTime" => get_the_time("Y-m-d H:m:s"),
			"postContent" => $content
		);
		$pdf24PostsArr[] = $pdf24Params;			
	}
}
	
rewind_posts();
	
	
$pdf24BlogArr = array(	
	"blogCharset" => get_bloginfo("charset"),
	"blogPosts" => count($pdf24PostsArr),
	"blogUrl" => get_bloginfo("siteurl"),
	"blogName" => get_bloginfo("name"),
	"blogValueEncoding" => "htmlSpecialChars"
);


function pdf24_getFormHiddenFields(&$formArr, $keyPrefix="", $keySuffix="") {	
	$out = "";
	foreach($formArr as $key => $val) {
		$val = htmlspecialchars($val);
		$out .= "<input type=\"hidden\" name=\"".$keyPrefix.$key.$keySuffix."\" value=\"".$val."\">\n";
	}	
	return $out;
}


echo "<form method=\"POST\" action=\"".$pdf24ScriptUrl."\" style=\"".$pdf24StyleForm."\" target=\"pdf24PopWin\" onsubmit=\"window.open('about:blank', 'pdf24PopWin', 'scrollbars=yes,width=400,height=200,top=0,left=0'); return true;\">\n";
echo pdf24_getFormHiddenFields($pdf24BlogArr);

$pdf24Count = 0;
foreach($pdf24PostsArr as $key=>$val) {
	echo pdf24_getFormHiddenFields($val, "", "_".$pdf24Count);	
	$pdf24Count++;
}

echo "<b>Send posts as PDF to</b>";	
echo " <input type=\"text\" name=\"sendEmailTo\" value=\"enter email here\" style=\"".$pdf24StyleInput."\" onMouseDown=\"this.value = '';\">";	
echo " <input type=\"submit\" value=\"send\" style=\"".$pdf24StyleButton."\">";
echo " <br> <a href=\"http://www.pdf24.org\" target=\"_blank\">www.pdf24.org</a>";
echo "</form>";

?>