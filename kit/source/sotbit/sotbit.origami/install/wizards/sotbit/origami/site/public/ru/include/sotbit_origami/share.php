<?
$url = str_replace('index.php','',$_SERVER['SCRIPT_URI']);
?>
<div class="col-12 product-detail-share">
	<div class="product-detail-share-block fonts__small_text">
		<div class="product-detail-share-block-button" onclick="showShares()">
			<div class="product-detail-share-block-button-text">
				Поделиться
			</div>
			<svg class="svg-icon-share-small" width="14" height="14">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_share_small"></use>
            </svg>
		</div>
		<span class="product-detail-share-block-comment fonts__middle_comment">
			Поделитесь товаром со своим друзьями и знакомыми
        </span>
		<ul class="sharing-buttons fonts__small_text" onclick="copyUrl();return false;" id="sharing-buttons">
			<li>
                <svg class="svg-social-icons social-icon-copy" width="13" height="13">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_copy"></use>
                </svg>
                Копировать ссылку
			</li>
			<li>
				<a
						target="popup"
						onclick="window.open('https://vk.com/share.php?url=<?=$url?>','popup','width=600,height=500'); return
								false; "
						href="https://vk.com/share.php?url=<?=$url?>">

					<span class="share-icon share-icon-vk">
                        <svg class="svg-social-icons social-icon-icon_vk" width="22px" height="22px">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_vk"></use>
                        </svg>
						<!-- <span class="share-vk"></span> -->
					</span>
					<span>Вконтакте</span>
				</a>
			</li>
			<li>
				<a
						target="popup"
						onclick="window.open('https://telegram.me/share/url?url=<?=$url?>','popup','width=600,height=500'); return
								false; "
						href="https://telegram.me/share/url?url=<?=$url?>">
					<span class="share-icon share-icon-telega">
                        <svg class="svg-social-icons social-icon-icon_vk" width="13" height="13">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_telegram"></use>
                        </svg>
						<!-- <span class="share-telega"></span> -->
					</span>
					<span>Телеграм</span>
				</a>
			</li>
			<li>
				<a
						target="popup"
						onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?=$url?>','popup','width=600,height=500'); return
								false; "
						href="https://www.facebook.com/sharer/sharer.php?u=<?=$url?>">
					<span class="share-icon share-icon-fb">
                        <svg class="svg-social-icons social-icon-icon_vk" width="13" height="13">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_facebook"></use>
                        </svg>
						<!-- <span class="share-fb"></span> -->
					</span>
					<span>Facebook</span>
				</a>
			</li>
			<li>
				<a
						target="popup"
						onclick="window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=<?=$url?>','popup','width=600,height=500'); return
								false; "
						href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=<?=$url?>">
					<span class="share-icon share-icon-ok">
                        <svg class="svg-social-icons social-icon-icon_vk" width="13" height="13">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_odnoklassniki"></use>
                        </svg>
						<!-- <span class="share-ok"></span> -->
					</span>
					<span>Одноклассники</span>
				</a>
            </li>
		</ul>
	</div>
</div>