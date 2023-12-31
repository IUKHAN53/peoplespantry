<?php

namespace LaraEditor\App\Editor;

class EditorConfig extends EditorBaseClass
{
    public string $container = '#editor';

    public bool $fromElement = false;

    public string $height = '100vh';

    public string $width = '100%';

    public ?EditorStorageManager $storageManager;

    public ?EditorAssetManager $assetManager;

    public array $pageManager;

    public EditorCanvas $canvas;

    public ?string $templatesUrl;

    public bool $forceClass = true;

    public bool $avoidInlineStyle = false;

    public bool $filemanagerUrl = false;
}
