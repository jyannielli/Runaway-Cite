<?php

class RunawayCitePlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_filters = array('item_citation');

public function filterItemCitation($citation, $args)
	{
		$citation = '';
        
        $creators = metadata('item', array('Item Type Metadata', 'Author'), array('all' => true));
        // Strip formatting and remove empty creator elements.
        $creators = array_filter(array_map('strip_formatting', $creators));
        if ($creators) {
            switch (count($creators)) {
                case 1:
                    $creator = $creators[0];
                    break;
                case 2:
                    /// Chicago-style item citation: two authors
                    $creator = __('%1$s and %2$s', $creators[0], $creators[1]);
                    break;
                case 3:
                    /// Chicago-style item citation: three authors
                    $creator = __('%1$s, %2$s, and %3$s', $creators[0], $creators[1], $creators[2]);
                    break;
                default:
                    /// Chicago-style item citation: more than three authors
                    $creator = __('%s et al.', $creators[0]);
            }
            $citation .= "$creator, ";
        }
        
  $title = metadata('item', array('Item Type Metadata', 'Runaway'), array('all' => true));
        // Strip formatting and remove empty creator elements.
        $titles = array_filter(array_map('strip_formatting', $title));
        if ($title) {
            switch (count($titles)) {
                case 1:
                    $title = "advertisement for $titles[0]";
                    break;
                case 2:
                    /// Chicago-style item citation: two authors
                    $title = __('advertisement for %1$s and %2$s', $titles[0], $titles[1]);
                    break;
                case 3:
                    /// Chicago-style item citation: three authors
                    $title = __('advertisement for %1$s, %2$s, and %3$s', $titles[0], $titles[1], $titles[2]);
                    break;
                default:
                    /// Chicago-style item citation: more than three authors
                    $title = __('advertisement for %s et al.', $titles[0]);
            }
            $citation .= "$title, ";
        }
        
        $newspaper = strip_formatting(metadata('item', array('Item Type Metadata', 'Newspaper')));
        if ($newspaper) {
            $citation .= "<em>$newspaper</em>, ";
        }

        $pubdate = strip_formatting(metadata('item', array('Item Type Metadata', 'Date')));
        if ($pubdate) {
            $citation .= "$pubdate, ";
        }
        
        $accessed = format_date(time(), Zend_Date::DATE_LONG);
        $url = html_escape(record_url('item', null, true));
        /// Chicago-style item citation: access date and URL
        $citation .= __('accessed %1$s, %2$s.', $accessed, $url);
        return $citation;
    }
}
