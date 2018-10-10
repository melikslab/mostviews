<section id="mostviews">
	<div class="row">
		{foreach $manufacturers as $item}
			<div class="col-sm-6">
				<h2>{l s='Seller of the month' mod='mostviews'}</h2>
				<a href="{$link->getmanufacturerLink($item.id_manufacturer, $item.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$item.name|escape:'htmlall':'UTF-8'}">
					<img data-src="{$img_manu_dir}{$item.id_manufacturer|escape:'htmlall':'UTF-8'}-zen_home_default_square.jpg" alt="{$item.name|escape:'htmlall':'UTF-8'}">
					<span class="name">{$item.name|truncate:18:'...'}</span>
				</a>

			</div>
		{/foreach}
		{foreach $products as $product}
			<div class="col-sm-6">
				<h2>{l s='Product of the month' mod='mostviews'}</h2>
				<a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}">
				{if $product.online_only}<div class="blur">{/if}
				<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'zen_home_default_square')|escape:'html'}" alt="{$product.name|escape:html:'UTF-8'}" class="product_image"/>
			  	{if $product.online_only}</div>{/if}
				<span class="name">{$product.name|truncate:45:'...'}</span>
				</a>
			</div>
		{/foreach}
	</div><!-- .row -->
</section><!-- .mostviews -->