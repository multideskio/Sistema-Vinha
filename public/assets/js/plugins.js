// Função para carregar scripts de forma dinâmica e retornar uma Promise
function loadScript(src) {
    return new Promise(function(resolve, reject) {
        var script = document.createElement('script');
        script.src = src;
        script.type = 'text/javascript';
        script.async = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}


// Garanta que o código seja executado após o carregamento do DOM
document.addEventListener('DOMContentLoaded', function() {
    Promise.all([
        loadScript('https://cdn.jsdelivr.net/npm/toastify-js'),
        loadScript('/assets/libs/choices.js/public/assets/scripts/choices.min.js'),
        loadScript('/assets/libs/flatpickr/flatpickr.min.js')
    ])
    .then(function() {
        // Inicialize as bibliotecas carregadas
        initializeLibraries();
        console.log('Todos os scripts foram carregados com sucesso!');
    })
    .catch(function(error) {
        //console.error('Erro ao carregar os scripts:', error);
    });
});
