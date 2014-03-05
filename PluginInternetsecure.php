<?php

/*****************************************************************/
// function plugin_internetsecure_variables($params) - required function
/*****************************************************************/
require_once 'modules/admin/models/GatewayPlugin.php';

/**
* @package Plugins
*/
class PluginInternetsecure extends GatewayPlugin
{
    function getVariables()
    {
        /* Specification
              itemkey     - used to identify variable in your other functions
              type        - text,textarea,yesno,password,hidden ( hiddens are not visable to the user )
              description - description of the variable, displayed in ClientExec
              value       - default value
        */

        $variables = array (
                   /*T*/"Plugin Name"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"How CE sees this plugin (not to be confused with the Signup Name)"/*/T*/,
                                        "value"         =>/*T*/"Internet Secure"/*/T*/
                                       ),
                   /*T*/"Company ID"/*/T*/ => array (
                                        "type"          =>"text",
                                        "description"   =>/*T*/"ID used to identify you to Internet Secure.<br>NOTE: This ID is required if you have selected Internet Secure as a payment gateway for any of your clients."/*/T*/,
                                        "value"         =>""
                                       ),
                   /*T*/"Visa"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow Visa card acceptance with this plugin.  No will prevent this card type."/*/T*/,
                                        "value"         =>"1"
                                       ),
                   /*T*/"MasterCard"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow MasterCard acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"1"
                                       ),
                   /*T*/"AmericanExpress"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow American Express card acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Discover"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow Discover card acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Invoice After Signup"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES if you want an invoice sent to the customer after signup is complete."/*/T*/,
                                        "value"         =>"1"
                                       ),
                   /*T*/"Signup Name"/*/T*/ => array (
                                        "type"          =>"text",
                                        "description"   =>/*T*/"Select the name to display in the signup process for this payment type. Example: eCheck or Credit Card."/*/T*/,
                                        "value"         =>"Credit Card"
                                       ),
                   /*T*/"Dummy Plugin"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"1 = Only used to specify a billing type for a customer. 0 = full fledged plugin requiring complete functions"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Accept CC Number"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Selecting YES allows the entering of CC numbers when using this plugin type. No will prevent entering of cc information"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Auto Payment"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"No description"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"30 Day Billing"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Select YES if you want ClientExec to treat monthly billing by 30 day intervals.  If you select NO then the same day will be used to determine intervals."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Check CVV2"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Select YES if you want to accept CVV2 for this plugin."/*/T*/,
                                        "value"         =>"0"
                                       )
        );
        return $variables;
    }

    function credit($params)
    {}

    /*****************************************************************/
    // function plugin_internetsecure_singlepayment($params) - required function
    /*****************************************************************/
    function singlepayment($params)
    {
        //Function needs to build the url to the payment processor
        //Plugin variables can be accesses via $params["plugin_[pluginname]_[variable]"] (ex. $params["plugin_paypal_UserID"])

        //generate post to submit to internetsecure
        $strRet = "<html>\n";
        $strRet .= "<head></head>\n";
        $strRet .= "<body>\n";
        $strRet .= "<form name=\"frmInternetSecure\" action=\"https://secure.internetsecure.com/process.cgi\" method=\"post\">\n";
        $strRet .= "<INPUT type=hidden name=\"products\" value=\"Price::Qty::Code::Desciption::Flags|".sprintf("%01.2f", round($params["invoiceTotal"], 2))."::1::".$params["invoiceNumber"]."::HostingInvoice::{".$params["currencytype"]."}\">";
        $strRet .= "<INPUT type=hidden name=\"MerchantNumber\" value=\"".$params["plugin_internetsecure_Company ID"]."\">";
        $strRet .= "<INPUT type=hidden name=\"language\" value=\"English\">";
        $strRet .= "<INPUT type=hidden name=\"ReturnURL\" value=\"".$params["companyURL"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxEmail\" value=\"".$params["userEmail"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxName\" value=\"".$params["userFirstName"]." ".$params["userLastName"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxCompany\" value=\"".$params["userCompany"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxCountry\" value=\"".$params["userCountry"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxAddress\" value=\"".$params["userAddress"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxCity\" value=\"".$params["userCity"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxProvince\" value=\"".$params["userState"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxPostal\" value=\"".$params["userZipcode"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxPhone\" value=\"".$params["userPhone"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxCurrency\" value=\"".$params["currencytype"]."\">";
        $strRet .= "<INPUT type=hidden name=\"xxxAmount\" value=\"".sprintf("%01.2f", round($params["invoiceTotal"], 2))."\">";
        $strRet .= "<script language=\"JavaScript\">\n";
        $strRet .= "document.forms['frmInternetSecure'].submit();\n";
        $strRet .= "</script>\n";
        $strRet .= "</form>\n";
        $strRet .= "</body></html>";
        echo $strRet;
        exit;
    }
}
?>
