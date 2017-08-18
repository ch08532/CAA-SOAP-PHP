<?php
// report variables
$acctnumber = "6202825451685005"; // acct number
$address = '123 main street';
$datetime = new DateTime('NOW'); //send report with current time
$datetime = $datetime->format('Y-m-d\TH:i:s'); //ex:'0001-01-01T00:00:00';
$dollarsSavedOnThisSale = 0;
$memcreditvalue = 0;
$noOfLitresPremFuel = 0;
$noOfLitresRegFuel = 0;
$clubcreditvalue = 0;
$totalDiscountAmount = 0;
$amountspend = 0;
$memberNumber = '6202825451685005';
//...add more vars to pass to xml post string to build your request properly  
$appId = "myappid"; //CAA app id
$appPwd = "myappPwd"; //CAA pwd id
$soapUrl = "https://uddi.caasco.ca/testAxisws/AffinityService.svc"; // url to CAA report webservice
// report soap xml post structure
$xml_post_string = '<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing">
<s:Header>
<a:Action s:mustUnderstand="1">https://uddi.caasco.ca/AxisWS/AffinityServiceProxy/IAffinityServiceProxy/UploadSingleRecord</a:Action>
<a:MessageID>urn:uuid:1c4f6a93-4ad2-42f9-8334-df012a2feb19</a:MessageID>
<ActivityId CorrelationId="d153d857-632a-4952-89d3-e6c9b7dcb752" xmlns="http://schemas.microsoft.com/2004/09/ServiceModel/Diagnostics">9c2a4de2-5aee-4174-8c49-0c911afeee1d</ActivityId>
<a:ReplyTo>
<a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address>
</a:ReplyTo>
<a:To s:mustUnderstand="1">https://uddi.caasco.ca/testAxisws/AffinityService.svc</a:To>
</s:Header>
<s:Body>
<UploadSingleRecord xmlns="https://uddi.caasco.ca/AxisWS/AffinityServiceProxy">
<_partnerData xmlns:b="https://uddi.caasco.ca/AxisWS" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
<b:AccountNumber i:nil="true">'.$acctnumber.'</b:AccountNumber>
<b:Address1 i:nil="true">'.$address.'</b:Address1>
<b:Address2 i:nil="true"></b:Address2>
<b:AmountSpend>' .$amountspend .'</b:AmountSpend>
<b:City i:nil="true"></b:City>
<b:ClubCreditValue>'.$clubcreditvalue.'</b:ClubCreditValue>
<b:ContestID i:nil="true"></b:ContestID>
<b:ContestWinner i:nil="true"></b:ContestWinner>
<b:CountryCode i:nil="true"></b:CountryCode>
<b:DateAndTime>'. $datetime .'</b:DateAndTime>
<b:DollarsSavedOnThisSale>'. $dollarsSavedOnThisSale . '</b:DollarsSavedOnThisSale>
<b:FirstName i:nil="true"></b:FirstName>
<b:LastName i:nil="true"></b:LastName>
<b:MemberCreditValue>' . $memcreditvalue .'</b:MemberCreditValue>
<b:MiddleName i:nil="true"></b:MiddleName>
<b:NoOfLitresPremiumFuel>' . $noOfLitresPremFuel .'</b:NoOfLitresPremiumFuel>
<b:NoOfLitresRegularFuel>' . $noOfLitresRegFuel .'</b:NoOfLitresRegularFuel>
<b:PaymentMethod i:nil="true"></b:PaymentMethod>
<b:Postalcode i:nil="true"></b:Postalcode>
<b:Province i:nil="true"></b:Province>
<b:Reserved2 i:nil="true"></b:Reserved2>
<b:Reserved3>0</b:Reserved3>
<b:Reserved4 i:nil="true"></b:Reserved4>
<b:Reserved5 i:nil="true"></b:Reserved5>
<b:SaleLocation i:nil="true"></b:SaleLocation>
<b:SaleType i:nil="true"></b:SaleType>
<b:SalesType1>0</b:SalesType1>
<b:SalesType2>0</b:SalesType2>
<b:SalesType3>0</b:SalesType3>
<b:ServiceDescription i:nil="true"></b:ServiceDescription>
<b:ServiceIncludedInSalesType2 i:nil="true"></b:ServiceIncludedInSalesType2>
<b:TotalDiscountAmount>' . $totalDiscountAmount .'</b:TotalDiscountAmount>
<b:TransactionNum i:nil="true"></b:TransactionNum>
<b:TransactionType i:nil="true"></b:TransactionType>
<b:UniformMemberNumber>'. $memberNumber .'</b:UniformMemberNumber>
<b:VinNumber i:nil="true"></b:VinNumber>
</_partnerData>
<_securityToken xmlns:b="https://uddi.caasco.ca" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
<b:ApplicationID>'.$appId.'</b:ApplicationID>
<b:ApplicationPassword>'.$appPwd.'</b:ApplicationPassword>
</_securityToken>
</UploadSingleRecord>
</s:Body>
</s:Envelope>';   
//set headers to the http call to CAA
$headers = array(
    "Content-type: application/soap+xml",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "Content-length: ".strlen($xml_post_string),
); 
$url = $soapUrl;
echo '----request---';
echo htmlentities( $xml_post_string);
// PHP cURL  for https connection with auth
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// send the request to CAA
$response = curl_exec($ch); 
// now print the XML formatted results for testing purposes
echo '----result--- ';
echo htmlentities( $response );
curl_close($ch);
// if a fault returned then report failed.
if(strpos($response, 's:Fault') == true) {
    echo 'Failure';
    exit;
} 
echo 'Success';
?>