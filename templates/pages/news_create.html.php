<div class="cabinet-page">
    <h1>Добавить новость</h1>
    <?php if (!empty($error)): ?>
        <div class="cabinet-card" style="border-left: 3px solid #ff6b6b;">
            <div class="cabinet-card-title"><img src="/images/icons/attention_gold.png" width="20" height="20" alt="!"> Ошибка</div>
            <div class="info-main-text"><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>
    <?php require_once __DIR__ . '/../../src/helpers/csrf.php'; ?>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/journal_12.png" width="20" height="20" alt="*">
            Новая запись
        </div>
        <form method="post" action="/news/store" class="login-form" style="margin-top:8px;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">

            <label for="title" class="form-label" style="display:block; margin-bottom:6px;">Заголовок</label>
            <input type="text" name="title" id="title" class="form-input" required placeholder="Введите заголовок" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <div class="form-hint" style="margin-top:8px;">Текст новости (поддерживаются базовые HTML-теги и изображения)</div>
            <textarea name="content" id="content" rows="10" class="form-input" style="min-height:180px;" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>

            <div class="form-hint" style="margin-top:8px;">Предпросмотр</div>
            <div id="news-preview" class="document-content" style="border:1px solid rgba(255,255,255,.08); padding:10px; min-height:120px; border-radius:6px;"></div>

            <button type="submit" class="restore-button" style="margin-top:12px;">
                <img src="/images/icons/add.png" width="16" height="16" alt="*" style="vertical-align: middle; margin-right: 6px;">
                Опубликовать
            </button>
        </form>
    </div>

    <div class="login-links" style="margin-top:12px;">
        <a href="/news/manage" class="link-item"><img src="/images/icons/arr_left.png" width="12" height="12" alt="*"> К списку новостей</a>
        <a href="/admin-panel" class="link-item"><img src="/images/icons/home.png" width="12" height="12" alt="*"> Админ-панель</a>
    </div>
</div>

<style>
.ck.ck-powered-by { display: none !important; }
.ck-editor__editable_inline { background: transparent !important; }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let ckEditorInstance;
ClassicEditor.create(document.querySelector('#content'), {
    toolbar: [
        'heading', '|', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'
    ],
    language: 'ru'
}).then(editor => {
    ckEditorInstance = editor;
    const editable = editor.ui.getEditableElement();
    editable.style.minHeight = '180px';
    editable.style.background = 'transparent';

    function updatePreview() {
        const title = document.getElementById('title').value || 'Заголовок';
        const content = editor.getData();
        document.getElementById('news-preview').innerHTML = `
            <div class="document-section">
                <h3 class="document-title">${title}</h3>
                <div class="document-content">${content}</div>
            </div>`;
    }
    editor.model.document.on('change:data', updatePreview);
    document.getElementById('title').addEventListener('input', updatePreview);
    updatePreview();
});
</script>
                </div>

