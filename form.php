<?php

$SendTo   = "your.email@youraddress.com"; // This is the ONLY thing you need to configure!

// ENTER BELOW AT YOUR OWN RISK !!!

$From     = $_POST['From'];
$Format   = $_POST['Format'];
$Subject  = $_POST['Subject'];
$Redirect = $_POST['Redirect'];

// Prevent these from showing up in the email body
unset($_POST['From']);
unset($_POST['Format']);
unset($_POST['Subject']);
unset($_POST['Redirect']);

if ($Format == 'xml')
{
	$data = string_to_xml($_POST);

} else {
	
	$data .= string_to_yaml($_POST); 
}

mail($SendTo, $Subject, $data, "From: $From");

header("Location: $Redirect");

//TODO verify that this is valid XML
function string_to_xml($ara,$parent = 'xml',$depth = 1)
{
	$output .= tabs($depth)."<$parent>\n";
	
	foreach($ara as $key => $value)
	{
		if(is_array($value))
		{
			$output.= string_to_xml($value,$key,$depth + 1)."\n";
		}
		else
		{
			$output.= tabs($depth + 1)."<".htmlspecialchars($key).'>'.$value.'</'.htmlspecialchars($key).">\n";
		}
	}
	$output.= tabs($depth).'</'.$parent.'>';

	return $output;
}
//TODO verify that this is valid YAML
function string_to_yaml($ara,$parent = 'xml',$depth = 1)
{
	$output = "\n";
	
	foreach($ara as $key => $value)
	{
		if(is_array($value))
		{
			$output .= tabs($depth).$key.':';
	
			$output.= string_to_yaml($value,$key,$depth + 1);
		}
		else
		{
			$output.= tabs($depth).htmlspecialchars($key).' : '.htmlspecialchars($value)."\n";
		}
	}
	return $output;
}
function tabs($tab = 1)
{
	for ($x = 0; $x < $tab; $x++)
	{
		$tabs .= "\t";
	}
	return $tabs;
}
?>
