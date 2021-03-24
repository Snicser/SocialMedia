// TODO If time is over make button in detail page to add product to cart
const modal = {
    /**
     * Creates and shows a modal to the user
     *
     * @param {string} id
     * @param username
     * @param commenter
     * @param comment
     */
    show: function (id, username, commenter, comment) {
        // Check if modal exists otherwise delete old modal
        if (document.getElementById(id)) {
            document.getElementById(id).remove();
        }

        // Create modal
        let HTMLStructure = `
                <div class="position-fixed overlay active">
        <div class="custom-modal position-fixed active" id="custom-modal">
            <div class="custom-modal-header mx-2 mt-1 d-flex justify-content-between align-items-center">
                <div class="custom-modal-title">Comments</div>
                <button onclick="modal.close('${id}')" class="close-button border-0">&times;</button>
            </div>

            <div class="custom-modal-body mx-2 my-1 d-flex flex-column">
                <div class="custom-modal-body-text container my-3">
                    <span id="description-title"><strong>Post van: ${username}</strong></span>
                    <hr class="my-2">
                    <div class="description-text">$
                        <span>Gebruiker: ${commenter} zegt: ${comment}</span>                    
                    </div>
                </div>

            </div>
        </div>
    </div>`;


        // Append to body
        let node = document.createElement("div");
        node.setAttribute("id", id);
        node.innerHTML = HTMLStructure;
        document.querySelector("body").appendChild(node);

        const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}`;
    },

    /**
     * Closes the modal
     *
     * @param {string} id
     * The modal ID is needed to close the modal.
     * The modal ID is the ID you given in modal.show() first parameter.
     */
    close: function (id) {
        // Check if modal exists
        if (document.getElementById(id)) {

            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);

            document.getElementById(id).remove();

            window.addEventListener('scroll', () => {
                document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
            });

        } else {
            console.log(`Modal with the ID: '${id}' does not exist...`);
        }
    },
};
