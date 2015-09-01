<?php

include_once('simple_html_dom.php');

$url = 'http://fisiomedicin.com';
$cleaning_tags = array('head','script');

// Extraigo la url en sus distintos componentes
$parse_url = parse_url($url);

$scheme = $parse_url['scheme'];
$host = $parse_url['host'];
$original_url = "$scheme"."://"."$host";
print_r($original_url);

//Debug: para eliminar
print_r($scheme);
print_r($host);


//$links = [];
/*
foreach($html->find('article') as $article) {
    $item['texto'] = $article->plaintext;
    $articles[] = $item;
}
print_r($articles);
*/

/*
foreach($html->find('article') as $article) {
       foreach($article->find('h1') as $h1){
       		$article['h1'] = $h1->
    }
}
*/



//Guarda los enlaces en un array

function links_extract($url){
	$links[] = $url;

	foreach ($links as $link) {
		$html = file_get_html($link);
		foreach($html->find('a') as $enlace) {
    		$links[] = $enlace->href;
		}
	}
	
	return $links;
}

//print_r(links_extract($url));



//Elimino los enlaces que no pertenecen al dominio y los duplicados

function clean_links($links, $original_url){

	foreach ($links as $key => $link) {
		$sch = parse_url($link)['scheme'];
		$ho = parse_url($link)['host'];
		$url_link = "$sch"."://"."$ho";
		//echo "estoy comprobando $url_link, que pertenece a $link </br>";
		if($url_link != $original_url){
			//echo "$url_link no es igual a $original_url y, por tanto, lo borro: </br>";
			//array_splice($links, $key);
		} else {
			$new_links[] = $link;
		  }
	}
	$res = array_unique($new_links);
	return $res;

}



//Extraer texto

function extraer_texto($url){
	
	print_r($html);
}

//Elimina las etiquetas que determinemos en $clean_tags

function clean_tags($tags, $url){
	$html = file_get_html($url);
	foreach ($tags as $tag) {
			echo "la tag es: $tag";
			foreach ($html->find($tag) as $movida) {
				echo "He encontrado la tag: <strong>$tag</strong> en <strong>$url</strong> y la voy a excluir";
				echo $tag->outertext = '';
			}
	}

}




//Guardo cada página en un archivo

$links = links_extract($url);
$cleaned_links = clean_links($links, $original_url);

/*
foreach ($cleaned_links as $link) {
	extraer_texto($link);
}
*/
print_r($cleaned_links);
print_r($cleaned_links[0]);

clean_tags(array('script','head'), 'http://fisiomedicin.com');
extraer_texto($cleaned_links[0]);


//echo $html;




?>