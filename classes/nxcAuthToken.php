<?php
/**
 * @author VaL <vd@nxc.no>
 * @copyright Copyright (C) 2013 NXC AS
 * @license GNU GPL v2
 * @package nxc_sso
 */

/**
 * Token object is used to handle authorizatrion:
 *    1. One domain creates a token
 *    2. Second domain takes and handles token, means it logs user in without requesting any loginnames or passwords
 *
 * @example nxcAuthToken::handle( nxcAuthToken::get()->create() );
 */
class nxcAuthToken
{
    /**
     * Dir will be used to store tokens
     */
    const CACHE_DIR = __CLASS__;

    /**
     * eZ User ID
     *
     * @var (string)
     */
    protected $UserID = false;

    /**
     * @var (string)
     */
    protected $Token = false;

    /**
     * @reimp
     */
    function __construct( $userID = false )
    {
        $this->UserID = $userID;
    }

    /**
     * @return (__CLASS__)
     */
    public static function get( $user = false )
    {
        if ( !$user )
        {
            $user = eZUser::instance();
        }

        return new self( $user->attribute( 'contentobject_id' ) );
    }

    /**
     * @return (eZUser)
     */
    public function getUser()
    {
        return $this->UserID ? eZUser::fetch( $this->UserID ) : false;
    }

    /**
     * @return (string)
     */
    public function getToken()
    {
        if ( !$this->Token )
        {
            $this->Token = md5( $this->UserID . time() . mt_rand() );
        }

        return $this->Token;
    }

    /**
     * Creates and stores token
     *
     * @var (string)
     */
    public function create()
    {
        if ( !$this->UserID )
        {
            return false;
        }

        $indexList = array( 'contentobject_id' => $this->UserID );
        $token = $this->getToken();
        $cache = new nxcCache( $token, self::CACHE_DIR );
        if ( $indexList )
        {
            nxcCache::clearByIndexList( $indexList );
            $cache->setIndexList( $indexList );
        }

        $cache->store( $this );

        return $token;
    }

    /**
     * @return (this)
     */
    public function remove()
    {
        $cache = new nxcCache( $this->Token, self::CACHE_DIR );
        $cache->delete();

        return $this;
    }

    /**
     * @param (string)
     * @return (__CLASS__)
     */
    public static function fetch( $token )
    {
        $cache = new nxcCache( $token, self::CACHE_DIR );
        if ( !$cache->exists() )
        {
            return false;
        }

        $o = $cache->getContent();

        return $o;
    }

    /**
     * Fetches token and logs user in
     *
     * @param (string)
     * @return (eZUser)
     */
    public static function handle( $token )
    {
        $sso = $token ? self::fetch( $token ) : false;
        if ( !$sso )
        {
            return false;
        }

        $sso->remove();

        $user = $sso->getUser();
        if ( !$user )
        {
            return false;
        }

        $user->loginCurrent();

        return $user;
    }

}
?>
