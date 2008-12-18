<?php
// Author  : Matt Lankford
// WebSite : ThePartTimeCTO.com/easymailer
// License : GPL
// Notes: 
// 
// Works with PHP 4.x Plus
//
// You can set the from address in a hidden form like the following:
// <input type="hidden" name="From" value="me@mydomain.com" />
// The "From" is case sensitive
// 
// You can set the format in a hidden form like the following:
// <input type="hidden" name="Format" value="xml" />
// The "Format" and "xml" are case sensitive
// 
// You can set the email subject in a hidden form like the following:
// <input type="hidden" name="Subject" value="My Email Subject" />
// The "Subject" is case sensitive
// 
// You can set the redirect page in a hidden form like the following:
// <input type="hidden" name="Redirect" value="http://mydomain.com/thankyou.html" />
// The "Redirect" is case sensitive
//
// Set this line to the address you want the email to be sent to.
// DO NOT set this from your form... it is a MAJOR security hole
// that will open your server up as an open relay and get you banned from the
// entire Internet for all time

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
