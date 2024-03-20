<?php

namespace Helpers;


class Constants {
    const EXPIRE_SPAN = 'P1D'; // アップロードしたファイルの有効期限
    const MAX_FILES=5;//一日当たりのアップロードファイル数
    const MAX_FILESIZE_SUM=10* 1024 * 1024;//一日当たりのアップロードサイズ上限
    const MAX_FILESIZE_UPLOAD=2 * 1024 * 1024;//一回当たりのアップロードサイズ上限

}