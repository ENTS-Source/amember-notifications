<?php

/**
 * Class Am_Plugin_EntsNotifications
 */
class Am_Plugin_EntsNotifications extends Am_Plugin
{
    const PLUGIN_STATUS = self::STATUS_PRODUCTION;
    const PLUGIN_COMM = self::COMM_FREE;
    const PLUGIN_REVISION = "1.0.0";

    static function getEtXml()
    {
        return file_get_contents("email-templates.xml");
    }

    function _initSetupForm(Am_Form_Setup $form)
    {
        $form->setTitle("ENTS: Notifications");
        $form->addElement("email_checkbox", "new_member")->setLabel(___("New Member Signup Email (to admin)"));
        $form->addFieldsPrefix("misc.ents-notifications.");
    }

    function onSetupEmailTemplateTypes(Am_Event $event)
    {
        /** @noinspection PhpParamsInspection */
        $event->addReturn(array(
            "id" => "misc.ents-notifications.new_member",
            "title" => ___("New Member Notification (to admin)"),
            "mailPeriodic" => Am_Mail::PRIORITY_LOW,
            "vars" => array('user')
        ), "misc.ents-notifications.new_member");
    }

    function onUserAfterInsert(Am_Event $event)
    {
        $user = $event->getUser();
        if ($this->getConfig("new_member") && $et = Am_Mail_Template::load("misc.ents-notifications.new_member", $user->lang)) {
            $et->setUser($user);
            $et->sendAdmin();
        }
    }
}