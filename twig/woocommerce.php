<?php
/*class WCtimber extends Timber\Post {

	var $is_on_sale;

	var $wc_product;

	private function get_wc_product()
	{
		
	}

	public function is_on_sale() {

		//always check wc product
		/*if(!$this->wc_product)
		{
			$product_field =  get_field("wc_product",$this->ID);
			$this->wc_product = $wc_get_product($product_field->ID);
		}

		if (!$this->is_on_sale) {
			$this->is_on_sale = $this->wc_product->is_on_sale();;
		}
		return $this->is_on_sale;
	}

	public function wc_price() {

		//always check wc product
		get_wc_product();


		if (!$this->is_on_sale) {
			$this->is_on_sale = $this->wc_product->is_on_sale();;
		}
		return $this->is_on_sale;
	}

	public function damnit() {
		return "damn it!";
	}


}*/

/**
 * Class BlogPost
 */
class BlogPost extends \Timber\Post {
   /**
    * Estimates time required to read a post.
    *
    * The words per minute are based on the English language, which e.g. is much
    * faster than German or French.
    *
    * @link https://www.irisreading.com/average-reading-speed-in-various-languages/
    *
    * @return string
    */
    public function reading_time() {
        $words_per_minute = 228;

        $words   = str_word_count( wp_strip_all_tags( $this->content() ) );
        $minutes = round( $words / $words_per_minute );

        /* translators: %s: Time duration in minute or minutes. */
        return sprintf( _n( '%s minute', '%s minutes', $minutes ), (int) $minutes );
    }
}