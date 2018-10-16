<section id="mostviews">
	<div class="row">
		<div class="col-sm-6">
				<h2>{l s='Sellers of the month' mod='mostviews'}</h2>
			{foreach $manufacturers as $item}
				<div class="col-xs-4">
					<a href="{$link->getmanufacturerLink($item.id_manufacturer, $item.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$item.name|escape:'htmlall':'UTF-8'}">
						<img src="{$img_manu_dir}{$item.id_manufacturer|escape:'htmlall':'UTF-8'}-small_default.jpg" alt="{$item.name|escape:'htmlall':'UTF-8'}">
						<span class="name">{$item.name|truncate:18:'...'}</span>
					</a>
				</div>
			{/foreach}
		</div>
		<div class="col-sm-6">
				<h2>{l s='Products of the month' mod='mostviews'}</h2>
			{foreach $products as $product}
				<div class="col-xs-4">
					<a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}">
					{if $product.online_only}<div class="blur">{/if}
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}" alt="{$product.name|escape:html:'UTF-8'}" class="product_image"/>
				  	{if $product.online_only}</div>{/if}
					<span class="name">{$product.name|truncate:45:'...'}</span>
					</a>
				</div>
			{/foreach}
		</div>
	</div><!-- .row -->
</section><!-- .mostviews -->