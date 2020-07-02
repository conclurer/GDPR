<div class="gdpr-form">

	<?= $text ?>

    <div class="center">
        <form class="accept-form">
            <input type="hidden" name="redirect" value="<?= $url ?>">
            <input type="hidden" name="enableBlocking" value="1">
            <input type="submit" class="gdpr-submit" value="<?= $optOut ?>">
        </form>
        <form class="deny-form">
            <input type="hidden" name="redirect" value="<?= $url ?>">
            <input type="hidden" name="disableBlocking" value="1">
            <input type="submit" class="gdpr-submit" value="<?= $optIn ?>">
        </form>
    </div>
</div>

<?=$style?>