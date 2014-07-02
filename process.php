<?php

ini_set('max_execution_time', 300);
include('simple_html_dom.php');
$html = new simple_html_dom;

$url = "http://www.pac.com.ve/paginas-blancas/blancas.php";

#keyword=&ubicacion=&pagina=

extract($_POST);

$ubicacion = str_replace(" ","+",$ubicacion);

$nameFile = preg_replace('/[,|\s|-]/',"+","$ubicacion-$keyword");

$nombres = explode(",",$keyword);

$dirFile = "files/" . sha1($nameFile) . ".json";

$resp = array();

if (! file_exists($dirFile))
{
	foreach ($nombres as $keyword){

		$keyword = str_replace(" ","+",trim($keyword));

		for ($page = 1, $switch = true; $switch; $page++){

			$html->load_file("$url?ubicacion=$ubicacion&keyword=$keyword&pagina=$page");

			if ($avisos = $html->find('div[id=paresult] div.aviso')){

				#for ($total)
				foreach ($avisos as $aviso){
					
					#var_dump($aviso->find('h3 a'),$aviso); exit;

					$node["nombre"] = $aviso->find('div.nombre a', 0)->innertext;
					$node["telefono"] = $aviso->find('div.telefono span', 0)->innertext;
					$j = 0;
					$direccion = "";
					foreach ($aviso->find('div.direccion span') as $ind => $dir){
						$direccion .= ($ind + 1) . ") " . $dir->innertext . " ";
					}
					$node["direccion"] = $direccion;

					$resp[] = $node;

					echo "<pre>";
					

				}

			}else{
				$switch = false;
			}

		}


	}

	$file = fopen($dirFile, "x");
	fwrite($file, json_encode($resp));
	fclose($file);


}


$data = json_decode(file_get_contents($dirFile));

if (! count($data))
	die ("No hay datos a exportar");

#var_dump($data);exit;

$str = "<table border='1'>";		

foreach ($data as $i => $node)
{
	if ($i == 0) #ESCRIBIR HEADER
	{	
		
		$str .= "<thead><tr><th>" . implode("</th><th>", array_keys((array)$node)) . "</th></tr></thead><tbody>";
	}
	
	$str .= "<tr><td>" . implode("</td><td>", (array)$node) . "</td></tr>";

}

$str .= "</tbody></table>";

if (mb_detect_encoding($str ) == 'UTF-8') {
   $str = mb_convert_encoding($str , "HTML-ENTITIES", "UTF-8");
}


header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-16");
header("Content-Disposition: filename=$nameFile.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $str; 

