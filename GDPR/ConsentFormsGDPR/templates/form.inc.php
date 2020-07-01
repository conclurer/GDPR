<div class="gdprform">

	<?= $text ?>

    <form class="accept-form">
        <input type="hidden" name="redirect" value="<?= $url ?>">
        <input type="hidden" name="enableBlocking" value="1">
        <input type="submit" class="gdpr-submit" value="<?=$optOut?>">
    </form>
    <form class="deny-form">
        <input type="hidden" name="redirect" value="<?= $url ?>">
        <input type="hidden" name="disableBlocking" value="1">
        <input type="submit" class="gdpr-submit" value="<?=$optIn?>">
    </form>
</div>

<style>
    .gdprform .accept-form,
    .gdprform .deny-form {
        float: left;
        width: 50%;
        padding: 15px;
    }

    .gdprform .accept-form .gdpr-submit,
    .gdprform .deny-form .gdpr-submit {
        width: 100%;
        padding: 15px;
        background: none;
        box-shadow: none;
        border-radius: 0;
        border: 0;
        cursor: pointer;
        color: white;
    }
    .gdprform .accept-form .gdpr-submit {
        background: #4cd137;
    }

    .gdprform .deny-form .gdpr-submit {
        background: #e84118;
    }
</style>