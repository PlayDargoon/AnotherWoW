        <style>
        /* Скрыть Powered by CKEditor */
        .ck.ck-powered-by { display: none !important; }
        /* Сделать фон редактора всегда прозрачным */
        .ck-editor__editable_inline {
            background: transparent !important;
        }
        </style>
<div class="body">
    <h2>Добавить новость</h2>
    <?php if (!empty($error)): ?>
        <div class="small" style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php require_once __DIR__ . '/../../src/helpers/csrf.php'; ?>
    <form method="post" action="/news/store">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
        <div class="pt">
            <label for="title">Заголовок:</label><br>
            <input type="text" name="title" id="title" required style="width:100%;">
        </div>
        <div class="pt">
            <label for="content">Текст новости (HTML, картинки, стили):</label><br>
            <textarea name="content" id="content" rows="8" required style="width:100%; min-height:180px;"></textarea>
            <div class="small" style="color:#888; margin-top:4px;">Можно использовать HTML-теги, вставлять картинки, менять цвет и стиль текста.</div>
            <div class="pt" id="editor-resize-controls">
                <button type="button" onclick="resizeEditor(60)">Увеличить</button>
                <button type="button" onclick="resizeEditor(-60)">Уменьшить</button>
            </div>
        </div>

        <div class="pt">
            <label>Предварительный просмотр:</label>
            <div id="news-preview" style="border:1px solid #ccc; min-height:120px; padding:10px; background:transparent;">
                 <!-- Если CKEditor не инициализирован, показывать обычный preview -->
            </div>
            <iframe id="news-preview-frame" style="width:100%; min-height:180px; border:none; background:transparent; display:none;"></iframe>
        </div>

        <!-- Визуальный редактор CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
        let ckEditorInstance;
        ClassicEditor.create(document.querySelector('#content'), {
            extraPlugins: [ window.SourceEditing ],
            toolbar: [
                'heading', '|', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertImage', 'undo', 'redo', 'code', 'sourceEditing', 'fontColor', 'fontBackgroundColor', 'alignment'
            ],
            language: 'ru',
            fontColor: {
                colors: [
                    {
                        color: '#000000',
                        label: 'Черный',
                        hasBorder: false
                    }
                ],
                columns: 5,
                documentColors: 0
            },
        }).then(editor => {
            ckEditorInstance = editor;
            const editable = editor.ui.getEditableElement();
            editable.style.minHeight = '180px';
            editable.style.color = '#000';
            editable.style.background = 'transparent';

            <div class="pt">
                <label for="content">Текст новости (HTML, картинки, стили):</label><br>
                <textarea name="content" id="content" rows="8" required style="width:100%; min-height:180px;"></textarea>
                <div class="small" style="color:#888; margin-top:4px;">Можно использовать HTML-теги, вставлять картинки, менять цвет и стиль текста.</div>
                <div class="pt" id="editor-resize-controls">
                    <button type="button" onclick="resizeEditor(60)">Увеличить</button>
                    <button type="button" onclick="resizeEditor(-60)">Уменьшить</button>
                </div>
            </div>

            <div class="pt">
                <label>Предварительный просмотр:</label>
                <div id="news-preview" style="border:1px solid #ccc; min-height:120px; padding:10px; background:transparent;"></div>
            </div>

            <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
            <script>
            let ckEditorInstance;
            ClassicEditor.create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|', 'bold', 'italic', 'underline', 'link', 'imageUpload', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo', 'code', 'fontColor', 'fontBackgroundColor', 'alignment'
                ],
                language: 'ru',
                image: {
                    toolbar: [ 'imageTextAlternative', 'imageStyle:full', 'imageStyle:side' ]
                },
            }).then(editor => {
                ckEditorInstance = editor;
                const editable = editor.ui.getEditableElement();
                editable.style.minHeight = '180px';
                editable.style.color = '#0066FF';
                editable.style.background = 'transparent';
                ckEditorInstance = editor;
                const editable = editor.ui.getEditableElement();
                editable.style.minHeight = '180px';
                editable.style.color = '#000';
                editable.style.background = 'transparent';

                // Предпросмотр
                function updatePreview() {
                    const author = window.sessionStorage.getItem('username') || 'Администрация';
                    const now = new Date();
                    const dateStr = now.toLocaleString('ru-RU', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                    const title = document.getElementById('title').value || 'Заголовок';
                    const content = editor.getData();
                    document.getElementById('news-preview').innerHTML = `
                        <div class="block-border" style="margin-bottom:18px;">
                            <div style="display:flex; align-items:center;">

