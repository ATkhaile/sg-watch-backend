<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AiTools extends Enum
{

    public const GENERATE_WEBSITE = 'generate_website';
    public const GET_CURRENT_USER = 'get_current_user';
    public const SEARCH_KNOWLEDGE = 'search_knowledge';
    public const GENERATE_IMAGE = 'generate_image';
    public const FETCH_URL_CONTENT = 'fetch_url_content';
    public const WEB_SEARCH = 'web_search';
    public const CODE_INTERPRETER = 'code_interpreter';


    public static function getAiTools()
    {
        return [
          [
            'name' => 'ウェブサイト生成',
            'code' => self::GENERATE_WEBSITE,
            'description' => '指定されたプロンプトに基づいてウェブサイトを生成します。',
          ],
          [
            'name' => '現在のユーザー取得',
            'code' => self::GET_CURRENT_USER,
            'description' => 'AI がログイン中のユーザー情報を DB から参照できるようにするためのツール',
          ],
          [
            'name' => 'ナレッジ検索',
            'code' => self::SEARCH_KNOWLEDGE,
            'description' => 'AI アプリケーションの知識ベースとして RAG データにアクセスする',
          ],
          [
            'name' => '画像生成',
            'code' => self::GENERATE_IMAGE,
            'description' => '指定されたプロンプトに基づいて画像を生成します。',
          ],
          [
            'name' => 'URLコンテンツ取得',
            'code' => self::FETCH_URL_CONTENT,
            'description' => '指定されたURLからコンテンツを取得します。',
          ],
          [
            'name' => 'ウェブ検索',
            'code' => self::WEB_SEARCH,
            'description' => 'インターネット上の情報をリアルタイムで検索します。(OpenAI専用)',
          ],
          [
            'name' => 'コードインタープリター',
            'code' => self::CODE_INTERPRETER,
            'description' => 'コードを実行し、計算、データ分析、ファイル操作などを行います。',
          ],
        ];
    }
}
