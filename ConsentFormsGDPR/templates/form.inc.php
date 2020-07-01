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

<style>
    .gdpr-form .accept-form,
    .gdpr-form .deny-form {
        display: inline;
        padding: 15px;
    }

    .gdpr-form .accept-form .gdpr-submit,
    .gdpr-form .deny-form .gdpr-submit {
        padding: 15px;
        background: none;
        box-shadow: none;
        border-radius: 0;
        border: 0;
        cursor: pointer;
        color: white;
    }

    .gdpr-form .accept-form .gdpr-submit {
        background: #4cd137;
    }

    .gdpr-form .deny-form .gdpr-submit {
        background: #e84118;
    }

    .gdpr-form .center{
        text-align: center;
    }
</style>