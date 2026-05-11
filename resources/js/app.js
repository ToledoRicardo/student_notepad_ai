import './bootstrap';

import Alpine from 'alpinejs';
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';
import 'font-awesome/css/font-awesome.min.css';

window.Alpine = Alpine;
window.EasyMDE = EasyMDE;

Alpine.start();

const initNoteEditor = () => {
    const textareas = document.querySelectorAll('textarea[data-easymde], textarea#content');
    if (!textareas.length) {
        return;
    }

    const underline = {
        name: 'underline',
        action(editor) {
            const cm = editor.codemirror;
            const selection = cm.getSelection();
            if (selection) {
                cm.replaceSelection(`<u>${selection}</u>`);
                return;
            }

            const cursor = cm.getCursor();
            cm.replaceRange('<u></u>', cursor);
            cm.setCursor({ line: cursor.line, ch: cursor.ch + 3 });
        },
        className: 'fa fa-underline',
        title: 'Subrayado',
    };

    textareas.forEach((textarea) => {
        if (textarea.dataset.easymdeInitialized === 'true') {
            return;
        }

        textarea.dataset.easymdeInitialized = 'true';

        new EasyMDE({
            element: textarea,
            spellChecker: false,
            status: false,
            autofocus: false,
            forceSync: true,
            autoDownloadFontAwesome: false,
            placeholder: 'Escribe tus apuntes en Markdown...',
            toolbar: [
                'bold',
                'italic',
                'heading',
                '|',
                underline,
                'strikethrough',
                '|',
                'quote',
                'unordered-list',
                'ordered-list',
                '|',
                'link',
                'code',
                'table',
                '|',
                'preview',
                'side-by-side',
                'fullscreen',
                'guide',
            ],
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNoteEditor);
} else {
    initNoteEditor();
}

window.addEventListener('load', initNoteEditor);
