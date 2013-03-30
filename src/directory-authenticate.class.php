<?php
/*
 * Module :     directory-authenticate.class.php
 * Project :    Directory Authenticate for PHP
 * Author :     Frédéric Libaud (http://www.libaudfrederic.fr)
 * Description :    This module is to defined interface to hown inherited class
 *                  to authenticate user with directory like AD, LDAP, ...
 * 
 * Project URL is http: https://github.com/Libaud/directory-authenticate-for-php.git
 * =============================================================================
 * history
 * =============================================================================
 *  18∕02/2013  Module creation for implementing ldap accessing class by
 *              Frédéric Libaud
 */

/* interface :  iDirectoyAuthentification
 * object : interface 
 */
interface iDirectoryAuthentification
{
    /* method : authenticateUser
     * desc. :  
     */
    
    function isUserExist($user);        
    /* method : authenticateUser
     * desc. :  
     */
    function getUserInformations($dn, $user);


    /* method : authenticateUser
     * desc. :  
     */
    public function authenticateUser($user, $password);
    
    /* method : authenticateUser
     * desc. :  
     */
    public function connect($login, $password);
}
    
?>
