<?php
/**
 * Class thats works with web page headers.
 *
 * @author  Leandro Antonello
 * @access  public
 * @package Core
 * @version 2.0
 * @date    2010-01-19
 */
class PageHeader
{
    // MEMBERS ====================================================================================
    /** @var PageHeader $instance The Singleton instance. */
    public static $instance;
    
    /** @var string Holds the page DocType. */
    private $DocType;
    /** @var string Holds the Content-Type of page. Defaults to [text/html]. */
    private $ContentType;
    /** @var string Holds the page Character Set. Defaults to [UTF-8]. */
    private $Charset;
    /** @var string Holds the language code of HTML. */
    private $LangCode;

    /** @var string Holds the page title, showing in the browsers title bar. */
    public $Title;
    /** @var string Holds a page description. */
    public $Description;
    /** @var string Holds the keywords. */
    public $Keywords;
    /** @var string Holds the page rating. */
    public $Rating;
    /** @var array Array for robots meta tags. */
    public $Robots;
    /** @var string Path to favorite icon. */
    public $Favicon;

    /** @var array Holds the various page headers. */
    private $Headers;
    /** @var array Holds a the META tags. */
    private $Metas;
    /** @var array Array for page styles (CSS). */
    private $Styles;
    /** @var array Array for page scripts (javascript). */
    private $Scripts;

    // DOCTYPE CONSTANTS ==========================================================================
    const DT_HTML_2      = '<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">';
    const DT_HTML_32     = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">';
    const DT_HTML_401_ST = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
    const DT_HTML_401_TR = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
    const DT_HTML_401_FS = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
    const DT_XHTML_10_BS = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">';
    const DT_XHTML_10_ST = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    const DT_XHTML_10_TR = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    const DT_XHTML_10_FS = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
    const DT_XHTML_11    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
    const DT_XHTML_11_BS = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">';
    const DT_HTML_5      = '<!DOCTYPE html>';

    // CONTENT TYPE CONSTANTS =====================================================================
    const CT_PLAIN = 'text/plain';
    const CT_HTML  = 'text/html';
    const CT_JAVAS = 'text/javascript';
    const CT_STYLE = 'text/stylesheet';

    // CHARACTER SET CONSTANTS ====================================================================
    const CH_UTF8  = 'UTF-8';
    const CH_ISO   = 'ISO-8859-1';

    // CONSTRUCTOR ================================================================================
    public function __construct()
    {
        $this->Title = 'SITE';
        $this->DocType = self::DT_XHTML_11;
        $this->ContentType = self::CT_HTML;
        $this->Charset = self::CH_UTF8;
        $this->LangCode = 'pt-br';

        $this->Description = null;
        $this->Keywords = null;
        $this->Rating = "General";
        $this->Robots = null;
        $this->Favicon = null;

        $this->Headers = null;
        $this->Metas   = null;
        $this->Styles  = null;
        $this->Scripts = null;

        $this->updateMetas();
    }

    // PUBLIC STATIC METHODS ======================================================================
    public static function getInstance()
    {
        if( !isset(self::$instance) )
        {
            self::$instance = new PageHeader();
        }

        return self::$instance;
    }


    // PROPERTIES =================================================================================
    /**
     * Sets the Title property.
     * @param string $newTitle The Site Title to be displayed in browser title bar.
     */
    public function setTitle( $newTitle ) { $this->Title = $newTitle; }
    /**
     * Sets the DocType property.
     * @param constant $newDocType One of the DocType Constants
     */
    public function setDocType( $newDocType ) { $this->DocType = $newDocType; }

    /**
     * Sets the Charset property.
     * @param string $newCharset The Charset.
     */
    public function setCharset( $newCharset ) { $this->Charset = $newCharset; $this->updateMetas(); }

    /**
     * Sets the Content Type property.
     * @param string $newContentType The new Content Type.
     */
    public function setContentType( $newContentType ) { $this->ContentType = $newContentType; $this->updateMetas(); }

    /**
     * Sets the Language Code of page.
     * @param string $newLang The new language code.
     */
    public function setLang( $newLang ) { $this->LangCode = $newLang; }

    // PUBLIC METHODS =============================================================================
    /** Write down all page header. */
    public function write()
    {
        // Write HTTP Headers:
        $this->writeHeaders();

        // Write HTML Doc -------------------------------------------------------------------------
        echo $this->DocType, "\r\n";
        echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'. $this->LangCode .'" lang="'. $this->LangCode .'">', "\r\n";
        echo '<head>', "\r\n";

        // Site Title
        echo '<title>', $this->Title, '</title>', "\r\n";

        // Metas ----------------------------------------------------------------------------------
        foreach( $this->Metas as $meta )
        {
            echo $meta, "\r\n";
        }

        // Description ----------------------------------------------------------------------------
        if( !is_null($this->Description) )
            echo '<meta name="description" content="'. $this->Description .'" />', "\r\n";

        // Keywords -------------------------------------------------------------------------------
        if( !is_null($this->Keywords) )
            echo '<meta name="keywords" content="'. $this->Keywords .'" />', "\r\n";

        // Rating ---------------------------------------------------------------------------------
        if( !is_null($this->Rating) )
            echo '<meta name="rating" content="'. $this->Rating .'" />', "\r\n";

        // Robots ---------------------------------------------------------------------------------
        if( !is_null($this->Robots) )
        {
            foreach( $this->Robots as $robot )
                echo '<meta name="'. $robot['name'] .'" content="'. $robot['content'] .'" />', "\r\n";
        }

        // Favicon --------------------------------------------------------------------------------
        if( !is_null($this->Favicon) )
        {
            echo '<link rel="shortcut icon" href="'. $this->Favicon .'" />', "\r\n";
            echo '<link rel="icon" href="'. $this->Favicon .'" />', "\r\n";
        }

        // TOKEN for avoiding cache.
        $token = microtime();

        // Styles ---------------------------------------------------------------------------------
        for($i = 0; $i < count($this->Styles); $i++)
        {
            $css = $this->Styles[$i]['file'];

            if( !$this->Styles[$i]['cache'] )
            {
                $css .= "?token=" . $token;
            }

            echo '<link rel="stylesheet" type="text/css" href="', $css,'" />', "\r\n";
        }

        // Scripts --------------------------------------------------------------------------------
        for($i = 0; $i < count($this->Scripts); $i++)
        {
            $js = $this->Scripts[$i]['file'];

            if( !$this->Scripts[$i]['cache'] )
            {
                $js .= "?token=" . $token;
            }

            echo '<script type="text/javascript" src="', $js,'"></script>', "\r\n";
        }

        echo '</head>', "\r\n";
    }
    /**
     * Adds a HTTP Header.
     * @param string $header_str Header with the same form of PHP's header() function.
     */
    public function addHeader( $header_str )
    {
        if( is_null($this->Headers) )
            $this->Headers = array();

        $this->Headers[] = $header_str;
    }

    /**
     * Defaults HTTP headers to avoiding page cache.
     */
    public function addDefaults()
    {
        if( is_null($this->Headers) )
            $this->Headers = array();

        $this->Headers[0] = "Expires: Mon, 26 Jul 1997 05:00:00 GMT";
        $this->Headers[1] = "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT";
        $this->Headers[2] = "Cache-Control: no-store, no-cache, must-revalidate";
        $this->Headers[3] = "Cache-Control: post-check=0, pre-check=0";
        $this->Headers[4] = "Pragma: no-cache";
    }

    /**
     * Adds a meta tag.
     * @param string $meta The entire meta tag as string.
     */
    public function addMeta( $meta )
    {
        $this->updateMetas();

        $this->Metas[] = $meta;
    }

    /**
     * Adds a robot specific meta tag.
     * @param string $robot_name Robot name (robots, googlebot, msnbot, slurp...)
     * @param string $content    The value (index, follow, noodp...)
     */
    public function addRobot( $robot_name, $content )
    {
        if( is_null($this->Robots) )
            $this->Robots = array();

        $this->Robots[] = array('name'=>$robot_name, 'content'=>$content);
    }

    /**
     * Adds a cascade style sheet file.
     * @param string $css        Absolute or relative path.
     * @param boolean $cacheable Indicates if the file will be cacheable. Defaults to TRUE.
     */
    public function addStyle( $css, $cacheable = true )
    {
        // Check if file is local or url
        if( strpos($css, "http") === false )
        {
            // File is local. Check if file exists.
            if( !file_exists($css) )
            {
                //App::error("Arquivo [$css] inexistente.", 0x0010);
                //return;
                throw new Exception("Arquivo [$css] inexistente.", 0x0010);
            }
        }

        if( is_null($this->Styles) )
            $this->Styles = array();

        $this->Styles[] = array('file'=>$css, 'cache'=>$cacheable);
    }
    /** Alias to addStyle() */
    public function addCss( $css, $cache = true ) { $this->addStyle($css, $cache); }

    /**
     * Adds a script file.
     * @param string $js         Absolute or relative path to file.
     * @param boolean $cacheable Indicates if the file will be cacheable. Defaults to TRUE.
     */
    public function addScript( $js, $cacheable = true )
    {
        // Check if JS is local or url
        if( strpos($js, "http") === false )
        {
            // File is local. Check if file exists
            if( !file_exists($js) )
            {
                //App::error("Arquivo [$js] inexistente.", 0x0011);
                //return;
                throw new Exception("Arquivo [$js] inexistente.", 0x0011);
            }
        }

        if( is_null($this->Scripts) )
            $this->Scripts = array();

        $this->Scripts[] = array('file'=>$js, 'cache'=>$cacheable);
    }
    /** Alias to addScript() */
    public function addJs( $js, $cache = true ) { $this->addScript($js, $cache); }

    /**
     * Adds a CSS or JS file without generates an error if file not exists.
     * @param string $css_or_js Path to CSS or JS file
     * @param bool $cacheable   Indicates if the file can be cacheable or not. Defaults to TRUE.
     */
    public function addSilently( $css_or_js, $cacheable = true )
    {
        // Check if file is local or remote
        if( strpos($css_or_js, "http") === false )
        {
            // Local file, check if file exists silently
            if( !file_exists($css_or_js) ) return;
        }

        // Check type
        $ext = substr($css_or_js, -3);

        switch( $ext )
        {
            case '.js':
                if( is_null($this->Scripts) )
                    $this->Scripts = array();

                $this->Scripts[] = array('file'=>$css_or_js, 'cache'=>$cacheable);
                break;
            case 'css':
                if( is_null($this->Styles) )
                    $this->Styles = array();

                $this->Styles[] = array('file'=>$css_or_js, 'cache'=>$cacheable);
                break;
        }
    }

    // PRIVATE METHODS ============================================================================
    /**
     * Write down all headers.
     */
    private function writeHeaders()
    {
        if( is_null($this->Headers) ) return;

        foreach($this->Headers as $hd)
        {
            header($hd);
        }

        header("Content-Type: ". $this->ContentType ."; charset=". $this->Charset ."");
    }

    /**
     * Updates the Meta tags.
     */
    private function updateMetas()
    {
        if( is_null($this->Metas) )
            $this->Metas = array();

        $this->Metas[0] = '<meta http-equiv="Content-Type" content="' . $this->ContentType . '; charset=' . $this->Charset . '" />';
    }

    // PUBLIC STATIC METHODS ======================================================================
    /**
     * Fast way to add the most common XHTML headers.
     * @param string $title   Page title.
     * @param string $charset Character SET of page ('UTF-8', 'ISO-8859-1', etc)
     * @param array $styles   Array of CSS files.
     * @param array $scripts  Array of javascript files.
     */
    public static function xhtmlHeaders( $title, $charset, $styles, $scripts )
    {
        //echo "PageHeaders::xhtmlHeaders('$title', '$charset', $styles, $scripts) <br />";
        // Check Arrays
        if( !is_array($styles) ) return;
        if( !is_array($scripts) ) return;

        // Create an instance
        $hd = new PageHeader($title);

        $hd->setCharset($charset);
        $hd->addDefaults();

        // Add Styles
        for($i = 0; $i < count($styles); $i++)
        {
            $hd->addCss($styles[$i]);
        }

        // Add Scripts
        for($i = 0; $i < count($scripts); $i++)
        {
            $hd->addJs($scripts[$i]);
        }

        // Write all
        $hd->write();
    }

    /**
     * Add headers to force download.
     * @param string $file Path to file
     * @return void
     */
    public static function forceDownload( $file )
    {
        // Check file
        if( !file_exists($file) ) return;

        // Add headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        // Flush
        ob_clean();
        flush();

        // Output file
        readfile($file);
    }

    /**
     * Static method to add headers that prevent page cache.
     * @param bool $output Indicates if headers will be printed out (true) or not (false).
     * @return mixed
     */
    public static function noCache( $output = true )
    {
        $headers = array();
        $headers[] = "Expires: Mon, 26 Jul 1997 05:00:00 GMT";
        $headers[] = "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT";
        $headers[] = "Cache-Control: no-store, no-cache, must-revalidate";
        $headers[] = "Cache-Control: post-check=0, pre-check=0";
        $headers[] = "Pragma: no-cache";

        if( $output )
        {
            foreach($headers as $hd)
            {
                header($hd);
            }
            return;
        }

        return $headers;
    }
}
?>
