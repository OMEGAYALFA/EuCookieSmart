{* todo refactor
/**
 * NOTICE OF LICENSE
 *
 * only18plus is a module for blocking and verifying user age
 * Copyright (C) 2017 Yuri Blanc
 * Email: info@yuriblanc.it
 * Website: www.yuriblanc.it
 *
 * This program is distributed WITHOUT ANY WARRANTY;
 */
*}

{if $lang_iso == 'it'}
    {assign var="cookieMsg" value="Questo sito utilizza cookie necessari al corretto funzionamento. Chiudendo questo banner, scorrendo questa pagina, cliccando su un link o proseguendo la navigazione in altra maniera, acconsenti all’uso dei cookie."}
    {assign var="cookieAccept" value="Accetto"}
    {assign var="cookiePolicy" value="Leggi l'informativa"}
{/if}
{if $lang_iso =='gb' || !$lang_iso}
    {assign var="cookiePolicy" value="Read cookies policy"}
    {assign var="cookieMsg" value="This site uses cookies required for correct operation. By closing this banner, scrolling the page, clicking on a link or continuing navigation in any other way, you consent to the use of cookies."}
    {assign var="cookieAccept" value="Accept"}
{/if}

<script>

    var euCookieSmartConfig = {
        message : '{$cookieMsg}',
        acceptButton: '{$EUCOOKIESMART_BUTTON_ACCEPT}',
        acceptText: '{$cookieAccept|escape:"quotes"}',
        declineButton: false,
        declineText: '{$EUCOOKIESMART_BUTTON_DECLINE_TEXT}',
        policyButton: '{$EUCOOKIESMART_BUTTON_POLICY}',
        policyText: '{$cookiePolicy|escape:"quotes"}',
        policyURL: '{$link->getCMSLink($EUCOOKIESMART_BUTTON_POLICY_ARTICLE)}',
        acceptOnContinue: '{$EUCOOKIESMART_ACCEPT_CONTINUE}',
        expireDays: '{$EUCOOKIESMART_EXPIRE_DAYS|escape:"quotes"}',
        renewOnVisit: '{$EUCOOKIESMART_RENEW_VISIT}',
        effect: '{$EUCOOKIESMART_EFFECT|escape:"quotes"}',
        fixed: '{$EUCOOKIESMART_FIXED|boolval}',
        bottom: '{$EUCOOKIESMART_BOTTOM}'
    };


</script>