const analyzer = document.getElementById('security-analyzer');
const toggleButton = document.getElementById('security-analyzer-toggle');
const closeButton = document.getElementById('security-analyzer-close');
const panel = document.getElementById('security-analyzer-panel');

const typeInput = document.getElementById('security-analyzer-type');
const indicatorInput = document.getElementById('security-analyzer-indicator');
const submitButton = document.getElementById('security-analyzer-submit');
const resultContainer = document.getElementById('security-analyzer-result');


/*
|--------------------------------------------------------------------------
| Abrir / cerrar analyzer
|--------------------------------------------------------------------------
*/

function openAnalyzer() {
    panel.classList.add('is-open');

    panel.setAttribute('aria-hidden', 'false');
    toggleButton.setAttribute('aria-expanded', 'true');
}


function closeAnalyzer() {
    panel.classList.remove('is-open');

    panel.setAttribute('aria-hidden', 'true');
    toggleButton.setAttribute('aria-expanded', 'false');
}


/*
|--------------------------------------------------------------------------
| Crear elemento de texto
|--------------------------------------------------------------------------
|
| Usamos textContent para que ningún contenido generado por Gemini
| sea interpretado como HTML.
|
*/

function createTextElement(tag, className, text) {
    const element = document.createElement(tag);

    element.className = className;
    element.textContent = text ?? '';

    return element;
}


/*
|--------------------------------------------------------------------------
| Crear métrica
|--------------------------------------------------------------------------
*/

function createMetric(label, value) {
    const metric = document.createElement('div');

    metric.className = 'security-analyzer-metric';

    const labelElement = createTextElement(
        'span',
        'security-analyzer-metric-label',
        label
    );

    const valueElement = createTextElement(
        'span',
        'security-analyzer-metric-value',
        String(value ?? 0)
    );

    metric.appendChild(labelElement);
    metric.appendChild(valueElement);

    return metric;
}


/*
|--------------------------------------------------------------------------
| Mostrar resultado estructurado
|--------------------------------------------------------------------------
*/

function showAnalysis(analysis) {

    /*
     * Limpiamos el resultado anterior.
     */
    resultContainer.replaceChildren();

    /*
     * Indicador
     */
    const indicator = createTextElement(
        'div',
        'security-analyzer-indicator',
        analysis.indicator
    );

    const type = createTextElement(
        'div',
        'security-analyzer-type',
        String(analysis.type || '').toUpperCase()
    );

    resultContainer.appendChild(indicator);
    resultContainer.appendChild(type);


    /*
     * Métricas
     */
    const metrics = document.createElement('div');

    metrics.className = 'security-analyzer-metrics';

    metrics.appendChild(
        createMetric('MALICIOSOS', analysis.malicious)
    );

    metrics.appendChild(
        createMetric('SOSPECHOSOS', analysis.suspicious)
    );

    metrics.appendChild(
        createMetric('INOFENSIVOS', analysis.harmless)
    );

    metrics.appendChild(
        createMetric('NO DETECTADOS', analysis.undetected)
    );

    metrics.appendChild(
        createMetric('TIMEOUT', analysis.timeout)
    );

    resultContainer.appendChild(metrics);


    /*
     * Reputación
     */
    const reputationSection = document.createElement('div');

    reputationSection.className =
        'security-analyzer-section';


    const reputationTitle = createTextElement(
        'div',
        'security-analyzer-section-title',
        'REPUTACIÓN'
    );

    const reputationValue = createTextElement(
        'div',
        'security-analyzer-reputation',
        String(analysis.reputation ?? 0)
    );

    reputationSection.appendChild(reputationTitle);
    reputationSection.appendChild(reputationValue);

    resultContainer.appendChild(reputationSection);


    /*
     * Resumen
     */
    const summarySection = document.createElement('div');

    summarySection.className =
        'security-analyzer-section';


    const summaryTitle = createTextElement(
        'div',
        'security-analyzer-section-title',
        'ANÁLISIS'
    );

    const summaryText = createTextElement(
        'p',
        'security-analyzer-section-text',
        analysis.summary
    );

    summarySection.appendChild(summaryTitle);
    summarySection.appendChild(summaryText);

    resultContainer.appendChild(summarySection);


    /*
     * Conclusión
     */
    const conclusionSection = document.createElement('div');

    conclusionSection.className =
        'security-analyzer-section';


    const conclusionTitle = createTextElement(
        'div',
        'security-analyzer-section-title',
        'CONCLUSIÓN'
    );

    const conclusionText = createTextElement(
        'p',
        'security-analyzer-section-text',
        analysis.conclusion
    );

    conclusionSection.appendChild(conclusionTitle);
    conclusionSection.appendChild(conclusionText);

    resultContainer.appendChild(conclusionSection);


    /*
     * Recomendación
     */
    const recommendationSection = document.createElement('div');

    recommendationSection.className =
        'security-analyzer-section';


    const recommendationTitle = createTextElement(
        'div',
        'security-analyzer-section-title',
        'RECOMENDACIÓN'
    );

    const recommendationText = createTextElement(
        'p',
        'security-analyzer-section-text',
        analysis.recommendation
    );

    recommendationSection.appendChild(recommendationTitle);
    recommendationSection.appendChild(recommendationText);

    resultContainer.appendChild(recommendationSection);


    /*
     * Mostrar resultado.
     */
    resultContainer.classList.add('is-visible');
}


/*
|--------------------------------------------------------------------------
| Mostrar mensaje simple
|--------------------------------------------------------------------------
*/

function showMessage(message) {

    resultContainer.replaceChildren();

    const messageElement = createTextElement(
        'p',
        'security-analyzer-message',
        message
    );

    resultContainer.appendChild(messageElement);

    resultContainer.classList.add('is-visible');
}


/*
|--------------------------------------------------------------------------
| Eventos
|--------------------------------------------------------------------------
*/

toggleButton.addEventListener('click', openAnalyzer);

closeButton.addEventListener('click', closeAnalyzer);


/*
|--------------------------------------------------------------------------
| Analizar indicador
|--------------------------------------------------------------------------
*/

submitButton.addEventListener('click', async () => {

    const type = typeInput.value;
    const indicator = indicatorInput.value.trim();


    /*
     * Validación
     */
    if (!indicator) {

        showMessage(
            'Ingresá un indicador para analizar.'
        );

        return;
    }


    /*
     * Estado de carga
     */
    submitButton.disabled = true;
    submitButton.textContent = 'Analizando...';

    showMessage(
        'Analizando indicador...'
    );


    try {

        /*
         * Frontend → CyberBlog
         */
        const response = await fetch(
            '/api/security-analyzer',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    type: type,
                    indicator: indicator
                })
            }
        );


        /*
         * Convertimos la respuesta HTTP
         * en un objeto JavaScript.
         */
        const data = await response.json();


        /*
         * Si PHP respondió con error HTTP,
         * lanzamos una excepción.
         */
        if (!response.ok) {

            throw new Error(
                data.message ||
                'Error al analizar el indicador.'
            );
        }


        /*
         * Ahora data.analysis es un OBJETO.
         */
        if (
            !data.analysis ||
            typeof data.analysis !== 'object'
        ) {

            throw new Error(
                'La respuesta del analizador no tiene el formato esperado.'
            );
        }


        /*
         * Renderizamos el objeto.
         */
        showAnalysis(data.analysis);


    } catch (error) {

        showMessage(
            error.message ||
            'No fue posible completar el análisis.'
        );


    } finally {

        /*
         * Restauramos el botón.
         */
        submitButton.disabled = false;

        submitButton.textContent = 'Analizar';
    }

});