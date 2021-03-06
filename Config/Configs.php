<?php
namespace Raman\Config;

/**
 * Define Application Variables
 * @author Mostafa Lavaei <lavaei@ramansoft.co>
 * @version 1.0
 */
class Raman_Config_Configs
{
	const LANGUAGE_NAME_ENGLISH 	= 'en';
	const LANGUAGE_NAME_PERSIAN 	= 'fa';
	
	/**
	 * The Zend_View instance
	 * @var \Zend_View
	 * @see \Zend_View
	 */
	protected $view;
	
	
	public function __construct()
	{
	    if(\Zend_Registry::isRegistered('view'))
	    {
		   $this->view 	= \Zend_Registry::get('view');
	    }
	    else 
	    {
	       $this->view = new \Zend_View();
	       
	       \Zend_Registry::set('view', $this->view);
	    }
	}
	
	/**
	 * Config doctrine with given parameters
	 * @param string $username
	 * @param string $password
	 * @param string $name
	 * @param string $server
	 */
	public function configDatabase($username, $password, $name, $server='localhost')
	{
		/*
		 * Code for doctrine connection 
		 */	
	}
	
	/**
	 * Set website path with protocol
	 * @param string $rootUrl
	 */
	public function configRootUrl($rootUrl)
	{
		/*
		 * ROOT_URL end with '/'
		 * this line correct it if not
		 */
		$rootUrl = trim($rootUrl, '/') . '/';
		
		/*
		 * Add protocol if not defined
		 */
		preg_match("@^([^/]*//).*$@", $rootUrl, $matches);//check if protocol has been defined
		
		if(!$matches[1])
		{
			if(DEFAULT_PROTOCOL)
				$rootUrl = DEFAULT_PROTOCOL . '://' . $rootUrl;
			else
				$rootUrl = 'http://' . $rootUrl;
		}

		
		define('ROOT_URL', $rootUrl);
	}
	
	/**
	 * Set website path with a secure protocol(ex. https//)
	 * @param string $rootUrl
	 */
	public function configSecureRootUrl($rootUrl)
	{
		/*
		 * ROOT_URL end with '/'
		 * this line correct it if not
		 */
		$rootUrl = trim($rootUrl, '/') . '/';
		
		/*
		 * Add protocol if not defined
		 */
		preg_match("@^([^/]*//).*$@", $rootUrl, $matches);//check if protocol has been defined
		
		if(!$matches[1])
		{
			if(DEFAULT_PROTOCOL)
				$rootUrl = DEFAULT_PROTOCOL . 's://' . $rootUrl;
			else
				$rootUrl = 'https://' . $rootUrl;
		}
		
		
		define('SECURE_ROOT_URL'	, $rootUrl);
	}	
	
	/**
	 * Set default protocol for site
	 * @param string $protocol
	 */
	public function configDefaultProtocol($protocol)
	{
		define('DEFAULT_PROTOCOL'	, $protocol);
	}
	
	/**
	 * Use for description meta tag. Description will shown in the search engines pages
	 * @param string $description
	 */
	public function configDescription($description)
	{
		$this->view->headMeta()->appendName('description', $description);
	}	
	
	/**
	 * Use for keywords meta tag
	 * @param string $keywords
	 */
	public function configKeywords($keywords)
	{		
		$this->view->headMeta()->appendName('keywords', $keywords);
	}	
	
	/**
	 * Set site language
	 * @param string $languageId The language id
	 * @param string $languageShortName Standard language name, it most be a two letter word
	 * @param string $languageFullName The name of language in this language
	 */
	public function configLanguage($languageId, $languageShortName, $languageFullName)
	{
	    $shortNameArray    = explode('_', $languageShortName);
	    $localeShortName   = $shortNameArray[0];
	    $countryShortName  = $shortNameArray[1];
	    
	    
		define('LANGUAGE_ID'		  , $languageId);
		define('LANGUAGE_SHORT_NAME'  , $languageShortName);
		define('LANGUAGE_FULL_NAME'	  , $languageFullName);
		define('LOCALE_SHORT_NAME'    , $localeShortName);
		define('COUNTRY_SHORT_NAME'	  , $countryShortName);
		
		$this->view->headMeta()->appendName('language', $languageShortName);
		
		$dirPath  = __DIR__ . "/../Translate/Languages/$languageShortName/";

		$filePath     = "$dirPath$languageShortName.mo";

		$translate    = new \Raman\Raman_Translate(array(
            'adapter' => \Raman\Raman_Translate::AN_GETTEXT,
		    'content' => $filePath,
		    'locale'  => $languageShortName
        ));
		
		
		$translateAdapter = $translate->getAdapter();
		
			
		$items    = scandir($dirPath);
		

		foreach ($items as $fileName)
		{			    
		    $filePath = "$dirPath$fileName";
		    
		    if(is_file($filePath))
		    {		        		        		        
		        if(preg_match("@^.+\.mo$@", $fileName))
		        {		            		            
		            $translateAdapter->addTranslation(array(
		                'adapter' => \Raman\Raman_Translate::AN_GETTEXT,
		                'content' => $filePath,
		                'locale'  => $languageShortName
		            ));
		        }
		    }
		}
		
		\Zend_Registry::set('translate', $translateAdapter);
	}		

	
	/**
	 * Use for author meta tag
	 * @param string $author
	 */
	public function configSiteAuthors($authors)
	{
		$this->view->headMeta()->appendName('authors', $authors);
	}
	
	/**
	 * Set page title
	 * @param string $title
	 * @param string $seperator
	 */
	public function configSiteTitle($title, $seperator)
	{
		$this->view->headTitle($title . $seperator);
	}
	
	/**
	 * 
	 * @param string $server Path to the piwik server
	 * @param int $id The site id in the piwik server
	 */
	public function configPiwik($server, $id)
	{
		/*
		 * piwik server path should end with '/' this line correct it if not
		 */
		$server = trim($server, '/') . '/';
		
		define('PIWIK_ADDRESS', $server);
		define('PIWIK_SITE_ID', $id);		
	}
	
	/**
	 * Set template for site
	 * @param integer $templateId The template id
	 * @param string $templateName The name of template
	 */
	public function configTemplate($templateId, $templateName)
	{
		define('TEMPLATE_Id', $templateId);
		define('TEMPLATE_NAME', $templateName);
	} 
	
	
	/**
	 * Configure Result Caching and Query Caching
	 * @param boolean $enable Set the Result/Query cache enable or disable
	 * @param string $type The cache driver
	 * @param number $lifeTime The cache lifetime in seconds
	 */
	public function configDataCaching($enable=true, $type='Apc', $lifeTime=3600)
	{
	    $driver = "\\Doctrine\\Common\\Cache\\" . $type . "Cache";
	    
	    $config = new \Doctrine\ORM\Configuration();
	    $config->setQueryCacheImpl(new $driver());
	    $config->setResultCacheImpl(new $driver());
	    $config->setMetadataCacheImpl(new $driver());
	    
	    \Zend_Registry::set('cacheDataEnable'  , $enable);
	    \Zend_Registry::set('cacheDataType'    , $type);
	    \Zend_Registry::set('cacheDataLifeTime', $lifeTime);
	}
		
}