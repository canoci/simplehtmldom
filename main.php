<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Title Page</title>

		<!-- Bootstrap CSS -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<h1 class="text-center">Hello World</h1>

		<!-- La parte php va aquí -->
			<?php

			include_once('simple_html_dom.php');

			$url = 'http://fisiomedicin.com';
			$cleaning_tags = array('head','script','style');

			// Extraigo la url en sus distintos componentes
			$parse_url = parse_url($url);

			$scheme = $parse_url['scheme'];
			$host = $parse_url['host'];
			$original_url = "$scheme"."://"."$host";
			print_r($original_url);

			//Debug: para eliminar
			print_r($scheme);
			print_r($host);

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

			//Elimino los enlaces que no pertenecen al dominio y los duplicados

			function clean_links($links, $original_url){

				foreach ($links as $key => $link) {
					$sch = parse_url($link, PHP_URL_SCHEME);
					$ho = parse_url($link, PHP_URL_HOST);
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

			function strip_tags_content($text, $tags = '', $invert = FALSE) { 

			  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
			  $tags = array_unique($tags[1]); 
			    
			  if(is_array($tags) AND count($tags) > 0) { 
			    if($invert == FALSE) { 
			      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
			    } 
			    else { 
			      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
			    } 
			  } 
			  elseif($invert == FALSE) { 
			    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
			  } 
			  return $text; 
			} 


			//Extraer texto

			function main($url){
				$html = file_get_html($url);
				$modified_text = strip_tags_content($html);
				print_r($modified_text);
			}


			//Elimina las etiquetas que determinemos en $cleaning_tags

			function clean_tags($tags, $url){
				$html = file_get_html($url);
				foreach ($tags as $tag) {
						echo "la tag es: $tag";
						foreach ($html->find($tag) as $movida) {
							echo "He encontrado la tag: <strong>$tag</strong> en <strong>$url</strong> y la voy a excluir";
							echo $movida->outertext = '';
						}
				}
				$html->save('result.html');
			}

			//Guardo cada página en un archivo

			$links = links_extract($url);
			$cleaned_links = clean_links($links, $original_url);


			print_r($cleaned_links);
			print_r($cleaned_links[0]);

			clean_tags($cleaning_tags, 'http://fisiomedicin.com');
			$plaintext = file_get_html('http://fisiomedicin.com')->plaintext; 
			var_dump($plaintext);


		?>


		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<!-- Underscore -->
		<script src = "https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
		<!-- Mi Javascript -->
		<script type="text/javascript">
			function toObject(arr){
				var rv = {};
				for (var i=0; i < arr.length; ++i)
					rv[i] =  arr[i];
				return rv;
			}
			

			function filterArray(arr1, arr2){
			  // Resta los elementos del array 1 con los del array 2
				console.log('Estoy ejecutando la funcion filterArray...');
				for (var i = 0; i <= arr1.length-1; i++) {
					for (var j = 0; j <= arr2.length-1; j++){
						if (arr1[i] == arr2[j]) {
							arr1.splice(i, 1);
							console.log("Elimino la palabra '" + arr2[j] + "' de la posición " + i);
						};
					}		
				};
				console.log('Estoy saliendo la funcion filterArray...');
			}

			function array_count_values(array) {
			  //  discuss at: http://phpjs.org/functions/array_count_values/
			  // original by: Ates Goral (http://magnetiq.com)
			  // improved by: Michael White (http://getsprink.com)
			  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
			  //    input by: sankai
			  //    input by: Shingo
			  // bugfixed by: Brett Zamir (http://brett-zamir.me)
			  //   example 1: array_count_values([ 3, 5, 3, "foo", "bar", "foo" ]);
			  //   returns 1: {3:2, 5:1, "foo":2, "bar":1}
			  //   example 2: array_count_values({ p1: 3, p2: 5, p3: 3, p4: "foo", p5: "bar", p6: "foo" });
			  //   returns 2: {3:2, 5:1, "foo":2, "bar":1}
			  //   example 3: array_count_values([ true, 4.2, 42, "fubar" ]);
			  //   returns 3: {42:1, "fubar":1}

			  var tmp_arr = {},
			    key = '',
			    t = '';

			  var __getType = function(obj) {
			    // Objects are php associative arrays.
			    var t = typeof obj;
			    t = t.toLowerCase();
			    if (t === 'object') {
			      t = 'array';
			    }
			    return t;
			  };

			  var __countValue = function(tmp_arr, value) {
			    switch (typeof value) {
			    case 'number':
			      if (Math.floor(value) !== value) {
			        return;
			      }
			      // Fall-through
			    case 'string':
			      if (value in tmp_arr && tmp_arr.hasOwnProperty(value)) {
			        ++tmp_arr[value];
			      } else {
			        tmp_arr[value] = 1;
			      }
			    }
			  };

			  t = __getType(array);
			  if (t === 'array') {
			    for (key in array) {
			      if (array.hasOwnProperty(key)) {
			        __countValue.call(this, tmp_arr, array[key]);
			      }
			    }
			  }

			  return tmp_arr;
			}

			var mensaje = "Hola mundo, soy una cadena de texto, de texto de verdad";
			var plaintext = "<?php echo $plaintext; ?>";
			var stopwords = ["una","soy"];
			var palabras = plaintext.split(" ");
			filterArray(palabras, stopwords);


			console.log(palabras);
			console.log(array_count_values(palabras))

		</script>
	</body>
</html>



