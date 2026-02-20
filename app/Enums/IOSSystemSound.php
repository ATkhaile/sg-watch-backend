<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class IOSSystemSound extends Enum
{
    public const DEFAULT = 'default';
    public const NEW_MAIL = 'new-mail.caf';
    public const MAIL_SENT = 'mail-sent.caf';
    public const VOICEMAIL = 'voicemail.caf';
    public const SMS_RECEIVED1 = 'sms-received1.caf';
    public const SMS_RECEIVED2  = 'sms-received2.caf';
    public const TWEET_SENT = 'tweet-sent.caf';
    public const ANTICIPATE = 'anticipate.caf';
    public const BLOOM = 'bloom.caf';
    public const CHOO_CHOO = 'choo-choo.caf';
    public const COMPLETE = 'complete.caf';
    public const HELLO = 'hello.caf';
    public const INPUT = 'input.caf';
    public const KEY_PRESS_CLICK = 'key-press-click.caf';
    public const MINI = 'mini.caf';
    public const PIANO_RIFF = 'piano-riff.caf';
    public const SOSUMI = 'sosumi.caf';
    public const SUBMARINE = 'submarine.caf';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DEFAULT:
                return 'Default';
            case self::NEW_MAIL:
                return 'New Mail';
            case self::MAIL_SENT:
                return 'Mail Sent';
            case self::VOICEMAIL:
                return 'Voicemail';
            case self::SMS_RECEIVED1:
                return 'SMS Received 1';
            case self::SMS_RECEIVED2:
                return 'SMS Received 2';
            case self::TWEET_SENT:
                return 'Tweet Sent';
            case self::ANTICIPATE:
                return 'Anticipate';
            case self::BLOOM:
                return 'Bloom';
            case self::CHOO_CHOO:
                return 'Choo Choo';
            case self::COMPLETE:
                return 'Complete';
            case self::HELLO:
                return 'Hello';
            case self::INPUT:
                return 'Input';
            case self::KEY_PRESS_CLICK:
                return 'Key Press Click';
            case self::MINI:
                return 'Mini';
            case self::PIANO_RIFF:
                return 'Piano Riff';
            case self::SOSUMI:
                return 'Sosumi';
            case self::SUBMARINE:
                return 'Submarine';
            default:
                return '';
        }
    }

    public static function parseArray(): array
    {
        $arr = [];
        foreach (self::getValues() as $value) {
            $arr[] = [
                'id' => $value,
                'label' => self::getDescription($value)
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return [
            self::DEFAULT,
            self::NEW_MAIL,
            self::MAIL_SENT,
            self::VOICEMAIL,
            self::SMS_RECEIVED1,
            self::SMS_RECEIVED2,
            self::TWEET_SENT,
            self::ANTICIPATE,
            self::BLOOM,
            self::CHOO_CHOO,
            self::COMPLETE,
            self::HELLO,
            self::INPUT,
            self::KEY_PRESS_CLICK,
            self::MINI,
            self::PIANO_RIFF,
            self::SOSUMI,
            self::SUBMARINE,
        ];
    }

    public static function fromString(string $type): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$type] ?? null;
    }
}
