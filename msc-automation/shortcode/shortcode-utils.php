<?php

class rss_feed {

    /**
     * Constructor
     *
     * @param array $a_db database settings
     * @param string $xmlns XML namespace
     * @param array $a_channel channel properties
     * @param string $site_url the URL of your site
     * @param string $site_name the name of your site
     * @param bool $full_feed flag for full feed (all topic content)
     */
    public function __construct($a_db, $xmlns, $a_channel, $site_url, $site_name, $full_feed = false) {
        // initialize
        $this->db_settings = $a_db;
        $this->xmlns = ($xmlns ? ' ' . $xmlns : '');
        $this->channel_properties = $a_channel;
        $this->site_url = $site_url;
        $this->site_name = $site_name;
        $this->full_feed = $full_feed;
    }

    /**
     * Generate RSS 2.0 feed
     *
     * @return string RSS 2.0 xml
     */
    public function create_feed() {

        $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";

        $xml .= '<rss version="2.0"' . $this->xmlns . '>' . "\n";

        // channel required properties
        $xml .= '<channel>' . "\n";
        $xml .= '<title>' . $this->channel_properties["title"] . '</title>' . "\n";
        $xml .= '<link>' . $this->channel_properties["link"] . '</link>' . "\n";
        $xml .= '<description>' . $this->channel_properties["description"] . '</description>' . "\n";

        // channel optional properties
        if (array_key_exists("language", $this->channel_properties)) {
            $xml .= '<language>' . $this->channel_properties["language"] . '</language>' . "\n";
        }
        if (array_key_exists("image_title", $this->channel_properties)) {
            $xml .= '<image>' . "\n";
            $xml .= '<title>' . $this->channel_properties["image_title"] . '</title>' . "\n";
            $xml .= '<link>' . $this->channel_properties["image_link"] . '</link>' . "\n";
            $xml .= '<url>' . $this->channel_properties["image_url"] . '</url>' . "\n";
            $xml .= '</image>' . "\n";
        }

        // get RSS channel items
        $now = date("YmdHis"); // get current time  // configure appropriately to your environment
        $rss_items = $this->get_feed_items($now);

        foreach ($rss_items as $rss_item) {
            $xml .= '<item>' . "\n";
            $xml .= '<title>' . $rss_item['title'] . '</title>' . "\n";
            $xml .= '<link>' . $rss_item['link'] . '</link>' . "\n";
            $xml .= '<description>' . $rss_item['description'] . '</description>' . "\n";
            $xml .= '<pubDate>' . $rss_item['pubDate'] . '</pubDate>' . "\n";
            $xml .= '<category>' . $rss_item['category'] . '</category>' . "\n";
            $xml .= '<source>' . $rss_item['source'] . '</source>' . "\n";

            if ($this->full_feed) {
                $xml .= '<content:encoded>' . $rss_item['content'] . '</content:encoded>' . "\n";
            }

            $xml .= '</item>' . "\n";
        }

        $xml .= '</channel>';

        $xml .= '</rss>';

        return $xml;
    }

    /**
     * @param $rss_date
     * @param $rss_items_count
     * @internal param $rss_items
     * @return array
     */
    public function get_feed_items($rss_date, $rss_items_count = 10) {

        // get rss items according to http://www.rssboard.org/rss-specification
        $a_rss_items = array();
        $a_rss_item = array();
        $topic = array();
        foreach ($a_topic_ids as $topic_id) {

            // title
            $a_rss_item['title'] = $topic['title'];

            // link
            $a_rss_item['link'] = $this->site_url . '/' . $topic['url'];

            // description
            $a_rss_item['description'] = '';

            if ($topic['image']) {
                $img_url = $this->site_url . $topic['image'];
                $a_rss_item['description'] = '<img src="' . $img_url . '" hspace="5" vspace="5" align="left"/>';
            }
            $a_rss_item['description'] .= $topic['description'];

            // pubdate -> configure appropriately to your environment
            $date = new DateTime($topic["date_published"]);
            $a_rss_item['pubDate'] = $date->format("D, d M Y H:i:s O");

            // category
            $a_rss_item['category'] = $topic["category"];

            // source
            $a_rss_item['source'] = $this->site_name;

            if ($this->full_feed) {
                // content
                $a_rss_item['content'] = '<![CDATA[' . $topic['topic_html'] . ']]>';
            }

            array_push($a_rss_items, $a_rss_item);
        }

        return $a_rss_items;
    }

}

function get_rss_feed($attributes) {
    if (is_admin()) {
        return;
    }
    if (!isset($attributes['url'])) {
        return;
    }    

    $url = $attributes['url'];
    $top = (isset($attributes['count'])) ? $attributes['count'] : 12;
    $call_action = (isset($attributes['callaction'])) ? $attributes['callaction'] : __('read more', 'msc-automation');

    $dom0 = new DOMDocument();
    $dom0->load($url);
    $titles = $dom0->getElementsByTagName("title");
    $date = $dom0->getElementsByTagName("pubDate");
    $link = $dom0->getElementsByTagName("link");
    $descrip = $dom0->getElementsByTagName("description");
    $n = $titles->length;
    if ($top < $n) {
        $n = $top;
    }
    $strecho = '';
    for ($i = 1; $i < $n; $i++) {
        if ($i > $top) {
            break;
        }
        if ($titles->item($i)) {

            /* try {
              $times = date("Y-m-d",(strtotime($date->item($i)->textContent)));
              } catch (Exception $e) {
              $times ='';
              } */

            $Titular = str_replace('?', "'", utf8_encode(utf8_decode($titles->item($i)->textContent)));
            $cos = str_replace('?', "'", utf8_encode(utf8_decode($descrip->item($i)->textContent)));
            $url = $link->item($i)->textContent;


            /* if (fmod($i,2) == 0 ){
              $strecho .= '<div class="team-member slideinleft">';
              }else{
              $strecho .= '<div class="team-member slideinright">';
              } */
            $strecho .= '<div>';
            $strecho .= '<h4>' . $Titular . '</h4>';
            $strecho .= '<p>' . $cos . '</p>';
            $strecho .= '<i><a href="' . $url . '" title="" target=_blank> '.$call_action.'</a></i>';

            $strecho .= '</div><br>';
        }
    }
    unset($dom0, $titles, $date, $link, $descrip);
    return $strecho;
}

add_shortcode('rss_feed', 'get_rss_feed');

