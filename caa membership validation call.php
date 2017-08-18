//Coupon code membership check example in php below (put in custom coupon code handler in magento):
<?php
$couponcode = "6202825451685005"; // CAA membership
$appId = "MemValMobilityUSATest"; //CAA app id
$appPwd = "d82bd9bf-7e9a-4b9f"; //CAA pwd id
$soapUrl = "https://uddirpqa.caasco.ca/ESB/QA/MembershipValidationWS/MembershipValidationService.svc"; // url to CAA webservice
// xml post structure
$xml_post_string = '<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing">
<s:Header>
<a:Action s:mustUnderstand="1">https://uddi.caasco.ca/MembershipValidationWS/MembershipValidationService/IMembershipValidation/ValidateMember</a:Action>
<a:MessageID>urn:uuid:f88f6a92-334b-45b2-877d-7a7c830c7d65</a:MessageID>
<ActivityId CorrelationId="6d1e0b18-2463-4929-b83d-786aeb673ee1" xmlns="http://schemas.microsoft.com/2004/09/ServiceModel/Diagnostics">0d5d854f-84c4-41b2-9af2-68dd0d2e3a93</ActivityId>
<a:ReplyTo>
<a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address>
</a:ReplyTo>
<a:To s:mustUnderstand="1">https://uddirpqa.caasco.ca/ESB/QA/MembershipValidationWS/MembershipValidationService.svc</a:To>
</s:Header>
<s:Body>
<ValidateMember xmlns="https://uddi.caasco.ca/MembershipValidationWS/MembershipValidationService">
<MembershipNumber>' . $couponcode . '</MembershipNumber>
<ESBSecurityToken xmlns:b="https://uddi.caasco.ca/ESBCoreLibrary" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
<b:ApplicationID>' . $appId . '</b:ApplicationID>
<b:ApplicationMethodName i:nil="true"></b:ApplicationMethodName>
<b:ApplicationPassword>' . $appPwd . '</b:ApplicationPassword>
</ESBSecurityToken>
</ValidateMember>
</s:Body>
</s:Envelope>';   
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
// if a fault returned then fail..else..parse the elements
if(strpos($response, 's:Fault') == true) {
    echo 'Failure';
    exit;
} else {
    //check if we have a valid response
    if (strpos($response, 'ValidateMemberResult') == true) {
        $dom = new domDocument;
        $dom->loadXML($response);
        if (!$dom) {
            echo 'Error while parsing the document';
            exit;
        }
        //now get the values from the response
        $MembershipStatus = $dom->getElementsByTagName('MembershipStatus')[0]->nodeValue;
        $MemberType = $dom->getElementsByTagName('MemberType')[0]->nodeValue;
        $MemberNumber = $dom->getElementsByTagName('MemberNumber')[0]->nodeValue;
        $OriginalMemberNumber = $dom->getElementsByTagName('OriginalMemberNumber')[0]->nodeValue;
        $LastName = $dom->getElementsByTagName('LastName')[0]->nodeValue;
        $FirstName = $dom->getElementsByTagName('FirstName')[0]->nodeValue;
        $MiddleNameInitial = $dom->getElementsByTagName('MiddleNameInitial')[0]->nodeValue;
        $MembershipExpiryDate = $dom->getElementsByTagName('MembershipExpiryDate')[0]->nodeValue;
        $Salutation = $dom->getElementsByTagName('Salutation')[0]->nodeValue;
        $Gender = $dom->getElementsByTagName('Gender')[0]->nodeValue;
        $MemberNameSuffix = $dom->getElementsByTagName('MemberNameSuffix')[0]->nodeValue;
        $Address1 = $dom->getElementsByTagName('Address1')[0]->nodeValue;
        $Address2 = $dom->getElementsByTagName('Address2')[0]->nodeValue;
        $StateProvince = $dom->getElementsByTagName('StateProvince')[0]->nodeValue;
        $PostalCode = $dom->getElementsByTagName('PostalCode')[0]->nodeValue;
        $PhoneNumber = $dom->getElementsByTagName('PhoneNumber')[0]->nodeValue;
        $AltPhoneNumber = $dom->getElementsByTagName('AltPhoneNumber')[0]->nodeValue;
        $MembershipStatus = $dom->getElementsByTagName('MembershipStatus')[0]->nodeValue;
        $CountryCode = $dom->getElementsByTagName('CountryCode')[0]->nodeValue;
        $YearOfJoin = $dom->getElementsByTagName('YearOfJoin')[0]->nodeValue;
        $ERSAbuse = $dom->getElementsByTagName('ERSAbuse')[0]->nodeValue;
        $Note = $dom->getElementsByTagName('Note')[0]->nodeValue;
        $RtnCheck = $dom->getElementsByTagName('RtnCheck')[0]->nodeValue;
    }
}
?>