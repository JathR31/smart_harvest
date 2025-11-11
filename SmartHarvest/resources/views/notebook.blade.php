<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHarvest Notebook</title>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-core.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-python.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nb-cell { border: 1px solid #e6e6e6; border-radius: .5rem; padding: 1rem; background: #fff; }
        .nb-code { background:#0b1220; color:#e6eef6; padding: .75rem; border-radius: .375rem; overflow:auto }
        .nb-md { color:#111827 }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <header class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold">SmartHarvest Notebook</h1>
            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-green-600">Back to site</a>
        </header>

        <div id="notebook" class="space-y-4">
            <div class="text-sm text-gray-500">Loading notebook...</div>
        </div>
    </div>

    <script>
        // Notebook JSON embedded server-side
        const notebookJson = {!! $notebookJson ?: 'null' !!};

        function renderCell(cell) {
            const container = document.createElement('div');
            container.className = 'nb-cell';

            if (cell.cell_type === 'markdown') {
                const md = cell.source.join('');
                const mdDiv = document.createElement('div');
                mdDiv.className = 'nb-md';
                mdDiv.innerHTML = marked.parse(md);
                container.appendChild(mdDiv);
            } else if (cell.cell_type === 'code') {
                const pre = document.createElement('pre');
                pre.className = 'nb-code language-python';
                const code = document.createElement('code');
                code.className = 'language-python';
                code.textContent = cell.source.join('');
                pre.appendChild(code);
                container.appendChild(pre);

                // show simple outputs (text/plain)
                if (cell.outputs && cell.outputs.length) {
                    cell.outputs.forEach(out => {
                        if (out.output_type === 'stream' && out.text) {
                            const o = document.createElement('pre');
                            o.className = 'mt-2 p-2 bg-gray-100 rounded text-sm';
                            o.textContent = out.text.join ? out.text.join('') : out.text;
                            container.appendChild(o);
                        } else if (out.output_type === 'execute_result' && out.data && out.data['text/plain']) {
                            const o = document.createElement('pre');
                            o.className = 'mt-2 p-2 bg-gray-100 rounded text-sm';
                            o.textContent = out.data['text/plain'].join ? out.data['text/plain'].join('') : out.data['text/plain'];
                            container.appendChild(o);
                        }
                    });
                }
            }
            return container;
        }

        (function() {
            const mount = document.getElementById('notebook');
            mount.innerHTML = '';
            if (!notebookJson) {
                mount.innerHTML = '<div class="text-red-600">Notebook file not found or empty.</div>';
                return;
            }

            const cells = notebookJson.cells || [];
            cells.forEach(cell => {
                const el = renderCell(cell);
                mount.appendChild(el);
            });

            // highlight code blocks
            if (window.Prism) Prism.highlightAll();
        })();
    </script>
</body>
</html>
