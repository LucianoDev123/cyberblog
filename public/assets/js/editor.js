document.addEventListener(
    'DOMContentLoaded',
    function () {

        const editor =
            document.getElementById(
                'editor'
            );


        const contenido =
            document.getElementById(
                'contenido'
            );


        const form =
            editor?.closest(
                'form'
            );


        const toolbar =
            document.getElementById(
                'editorToolbar'
            );


        /*
         * Si no existe el editor,
         * no hacemos nada.
         */
        if (
            !editor ||
            !contenido ||
            !form
        ) {
            return;
        }


        /*
         * ==================================================
         * SINCRONIZACIÓN DEL CONTENIDO
         * ==================================================
         */

        function synchronizeContent() {

            contenido.value =
                editor.innerHTML;
        }


        /*
         * Cada vez que el usuario escribe,
         * actualizamos el textarea oculto.
         */
        editor.addEventListener(
            'input',
            synchronizeContent
        );


        /*
         * Antes de enviar el formulario,
         * copiamos el contenido HTML completo.
         */
        form.addEventListener(
            'submit',
            function () {

                synchronizeContent();
            }
        );


        /*
         * ==================================================
         * BARRA DE HERRAMIENTAS
         * ==================================================
         */

        if (toolbar) {

            /*
             * Evitamos perder el foco
             * del editor al hacer clic
             * sobre los botones.
             */
            toolbar.addEventListener(
                'mousedown',
                function (event) {

                    event.preventDefault();
                }
            );


            /*
             * Botones que utilizan
             * document.execCommand().
             */
            const commandButtons =
                toolbar.querySelectorAll(
                    '[data-command]'
                );


            commandButtons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const command =
                                button.dataset.command;


                            editor.focus();


                            document.execCommand(
                                command,
                                false,
                                null
                            );


                            synchronizeContent();
                        }
                    );
                }
            );


            /*
             * ==================================================
             * FORMATOS DE BLOQUE
             * ==================================================
             */

            const formatButtons =
                toolbar.querySelectorAll(
                    '[data-format]'
                );


            formatButtons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const format =
                                button.dataset.format;


                            editor.focus();


                            document.execCommand(
                                'formatBlock',
                                false,
                                format
                            );


                            synchronizeContent();
                        }
                    );
                }
            );


            /*
             * ==================================================
             * BLOQUE DE CÓDIGO
             * ==================================================
             */

            const codeButton =
                toolbar.querySelector(
                    '[data-code]'
                );


            if (codeButton) {

                codeButton.addEventListener(
                    'click',
                    function () {

                        editor.focus();


                        document.execCommand(
                            'formatBlock',
                            false,
                            'pre'
                        );


                        synchronizeContent();
                    }
                );
            }


            /*
             * ==================================================
             * INSERTAR ENLACE
             * ==================================================
             */

            const linkButton =
                document.getElementById(
                    'linkButton'
                );


            if (linkButton) {

                linkButton.addEventListener(
                    'click',
                    function () {

                        editor.focus();


                        const url =
                            prompt(
                                'Introduce la URL del enlace:'
                            );


                        /*
                         * Si el usuario cancela
                         * o deja vacío, no hacemos nada.
                         */
                        if (
                            !url ||
                            url.trim() === ''
                        ) {
                            return;
                        }


                        let finalUrl =
                            url.trim();


                        /*
                         * Si el usuario escribió
                         * una URL sin protocolo,
                         * agregamos https://
                         */
                        if (
                            !/^https?:\/\//i.test(
                                finalUrl
                            )
                        ) {

                            finalUrl =
                                'https://'
                                + finalUrl;
                        }


                        document.execCommand(
                            'createLink',
                            false,
                            finalUrl
                        );


                        synchronizeContent();
                    }
                );
            }


            /*
             * ==================================================
             * LÍNEA HORIZONTAL
             * ==================================================
             */

            const horizontalRuleButton =
                document.getElementById(
                    'horizontalRuleButton'
                );


            if (horizontalRuleButton) {

                horizontalRuleButton.addEventListener(
                    'click',
                    function () {

                        editor.focus();


                        document.execCommand(
                            'insertHorizontalRule',
                            false,
                            null
                        );


                        synchronizeContent();
                    }
                );
            }


            /*
             * ==================================================
             * LIMPIAR FORMATO
             * ==================================================
             */

            const removeFormatButton =
                document.getElementById(
                    'removeFormatButton'
                );


            if (removeFormatButton) {

                removeFormatButton.addEventListener(
                    'click',
                    function () {

                        editor.focus();


                        document.execCommand(
                            'removeFormat',
                            false,
                            null
                        );


                        synchronizeContent();
                    }
                );
            }
        }


        /*
         * ==================================================
         * PEGADO DE IMÁGENES
         * ==================================================
         */

        editor.addEventListener(
            'paste',
            async function (event) {

                const clipboard =
                    event.clipboardData;


                if (!clipboard) {
                    return;
                }


                let imageFile =
                    null;


                /*
                 * Buscamos una imagen
                 * dentro del portapapeles.
                 */
                for (
                    const item
                    of clipboard.items
                ) {

                    if (
                        item.type.startsWith(
                            'image/'
                        )
                    ) {

                        imageFile =
                            item.getAsFile();

                        break;
                    }
                }


                /*
                 * Si no se pegó una imagen,
                 * permitimos el pegado normal
                 * de texto.
                 */
                if (!imageFile) {
                    return;
                }


                /*
                 * Evitamos que el navegador
                 * inserte la imagen directamente.
                 */
                event.preventDefault();


                /*
                 * ==================================================
                 * GUARDAMOS EL CURSOR
                 * ==================================================
                 */

                const selection =
                    window.getSelection();


                let range =
                    null;


                if (
                    selection &&
                    selection.rangeCount > 0
                ) {

                    range =
                        selection
                            .getRangeAt(0)
                            .cloneRange();
                }


                /*
                 * ==================================================
                 * CREAMOS EL FORMDATA
                 * ==================================================
                 */

                const formData =
                    new FormData();


                formData.append(
                    'image',
                    imageFile
                );


                /*
                 * ==================================================
                 * CSRF TOKEN
                 * ==================================================
                 */

                const csrfToken =
                    form.querySelector(
                        'input[name="csrf_token"]'
                    );


                if (csrfToken) {

                    formData.append(
                        'csrf_token',
                        csrfToken.value
                    );
                }


                /*
                 * ==================================================
                 * INDICADOR DE CARGA
                 * ==================================================
                 */

                const loadingElement =
                    document.createElement(
                        'span'
                    );


                loadingElement.textContent =
                    ' Subiendo imagen...';


                loadingElement.style.fontSize =
                    '14px';


                loadingElement.style.fontStyle =
                    'italic';


                /*
                 * Insertamos temporalmente
                 * el indicador donde estaba
                 * el cursor.
                 */
                if (range) {

                    range.insertNode(
                        loadingElement
                    );

                } else {

                    editor.appendChild(
                        loadingElement
                    );
                }


                try {

                    /*
                     * ==================================================
                     * SUBIMOS LA IMAGEN
                     * ==================================================
                     */

                    const response =
                        await fetch(
                            '/incuyo/cyberblog/public/admin/upload/image',
                            {

                                method:
                                    'POST',

                                body:
                                    formData
                            }
                        );


                    const result =
                        await response.json();


                    /*
                     * Eliminamos el indicador.
                     */
                    loadingElement.remove();


                    /*
                     * Comprobamos la respuesta.
                     */
                    if (
                        !response.ok ||
                        !result.success
                    ) {

                        throw new Error(
                            result.message ||
                            'No fue posible subir la imagen.'
                        );
                    }


                    /*
                     * ==================================================
                     * CREAMOS LA IMAGEN
                     * ==================================================
                     */

                    const image =
                        document.createElement(
                            'img'
                        );


                    image.src =
                        result.url;


                    image.alt =
                        'Imagen insertada en el artículo';


                    image.style.display =
                        'block';


                    image.style.maxWidth =
                        '100%';


                    image.style.height =
                        'auto';


                    image.style.margin =
                        '20px 0';


                    /*
                     * ==================================================
                     * INSERTAMOS LA IMAGEN
                     * ==================================================
                     */

                    if (range) {

                        range.insertNode(
                            image
                        );


                        /*
                         * Agregamos una nueva línea
                         * después de la imagen.
                         */
                        const newLine =
                            document.createElement(
                                'br'
                            );


                        image.after(
                            newLine
                        );


                        /*
                         * Movemos el cursor después
                         * de la imagen.
                         */
                        const newRange =
                            document.createRange();


                        newRange.setStartAfter(
                            newLine
                        );


                        newRange.collapse(
                            true
                        );


                        if (selection) {

                            selection.removeAllRanges();


                            selection.addRange(
                                newRange
                            );
                        }


                    } else {

                        editor.appendChild(
                            image
                        );


                        editor.appendChild(
                            document.createElement(
                                'br'
                            )
                        );
                    }


                    /*
                     * Guardamos el nuevo HTML.
                     */
                    synchronizeContent();


                } catch (error) {

                    loadingElement.remove();


                    console.error(
                        'Error al subir imagen:',
                        error
                    );


                    alert(
                        error.message ||
                        'Ocurrió un error al subir la imagen.'
                    );
                }
            }
        );
    }
);