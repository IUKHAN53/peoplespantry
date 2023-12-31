<?php

namespace LaraEditor\App\Editor;

class EditorAssetManager
{
    public array $assets = [];

    public ?string $upload = null;

    public ?string $uploadName = 'file';

    public array $headers = [];

    public int $autoAdd = 1;

    public string $uploadText = 'Drop files here or click to upload';

    public string $addBtnText = 'Add File';

    public int $dropzone = 1;

    public int $openAssetsOnDrop = 0;

    public string $modalTitle = 'Upload Files';

    public bool $showUrlInput = true;
}
