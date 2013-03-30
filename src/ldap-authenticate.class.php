<?php
/*
 * Module :     ldap-authenticate.class.php
 * Project :    Directory Authenticate for PHP
 * Author :     Frédéric Libaud (http://www.libaudfrederic.fr)
 * Description :    This module is to defined class to accessing
 *                  to LDAP directory with PHP API
 * 
 * Project URL is http: https://github.com/Libaud/directory-authenticate-for-php.git
 * =============================================================================
 * history
 * =============================================================================
 *  18∕02/2013  Module creation for implementing ldap authenticating class by
 *              Frédéric Libaud
 */

include_once(dirname(__FILE__).'/directory-authenticate.class.php');
include_once(dirname(__FILE__).'/../../directory-access/src/ldap-directory-access.class.php');

/* class : cLDAPAuthentification
 * description: class for authenticate user on LDAP directory
 * 
 * history :
 *      First implementation on february/march 2013
 */
class cLDAPAuthentification implements iDirectoryAuthentification
{
    // private member's
    private $oDAO;                          // cLDAPDirectoryAccess instance
    private $userSearchFilter;              // filter to search user uid by default
    private $dn;                            // LDAP domain like dc=example,dc=com
    private $ou;                            // LDAP object unit like ou=people

    // Constructor's and destructor's

    /* method : __construct
     *  desc. : constructor
     * params :
     *          $host : url or ip for host
     *          $port : $port ofr host
     * return : instance
     */
    function __construct($host, $port)
    {
        $this->userSearchFilter = 'uid';
        $this->oDAO = new cLDAPDirectoryAccess($host, $port);                
    }

    // overrided abstract protected method's

    // public method's

    /* method :
    *  desc. :
    * params :
    * return :
    */
    public function connect($login, $password)
    {
        $this->oDAO->setDN($this->dn);
        $this->oDAO->connect($login, $password);
    }
    
    public function connectWithTLS($login, $password)
    {
        $this->oDAO->setDN($this->dn);
        $this->oDAO->connectWithTLS($login, $password);        
    }
    
    public function anonymousConnect()
    {
        $this->oDAO->setDN($this->dn);
        $this->oDAO->anonymousConnect();   
    }
    
    /* method : authenticateUser
     *  desc. : methode for authenticate user
     * params :
     *          $user :     user login
     *          $passwowd : user password
     * return : boolean, true if user is authenticated or false if not
     */
    public function authenticateUser($user, $password)
    {        
        $result = true;
        if ($this->isUserExist($user))
        {
            $userBindString = $this->userSearchFilter.'='.$user;
            if (!empty($this->ou))
            {
                $userBindString .= ','.$this->ou;
            }
            if (!empty($this->dn))
            {
                $userBindString .= ','.$this->dn;
            }
            try {
                if (!$this->oDAO->bind($userBindString, $password))
                    $result = false;
            } catch (cLDAPException $e)
            {
                $result = false;
            }
       }
        else
            $result = false;
        return $result;
    }

    // properties access method's

    /* method :
    *  desc. :
    * params :
    * return :
    */
    function getUserSearchFilter()
    {
        return $this->_userSearchFilter;
    }
    
    function setUserSearchFilter($filter)
    {
        $this->_userSearchFilter = $filter;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setSearchUser($searchFilter)
    {
        $this->_userSearchFilter = $searchFilter;            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function isUserExist($user)
    {
        $result = false;
        if ($this->getUserInformations($this->dn, $user) != null)
            $result = true;
        return $result;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getUserInformations($dn, $user)
    {   
        return $this->oDAO->searchWithFilter($dn, $this->userSearchFilter.'='.$user);
    }

    public function getDN()
    {
        return $this->dn;
    }
    
    public function setDN($dn)
    {
        $this->dn = $dn;
    }
    
    public function getOU()
    {
        return $this->ou;
    }
    
    public function setOU($ou)
    {
        $this->ou = $ou;
    }
}
    
?>
