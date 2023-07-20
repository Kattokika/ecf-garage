import Uppy from '@uppy/core';
import Dashboard from '@uppy/dashboard';
import XHR from '@uppy/xhr-upload';
import ImageEditor from '@uppy/image-editor';
import French from '@uppy/locales/lib/fr_FR';


import '@uppy/core/dist/style.min.css';
import '@uppy/dashboard/dist/style.min.css';
import '@uppy/image-editor/dist/style.min.css';

const uppy = new Uppy({
        locale: French,
        restrictions: {
            allowedFileTypes: ["image/*"],
            maxNumberOfFiles: 10,
        },
    })
    .use(
        Dashboard,
        {
            inline: true,
            target: '#uppy-dashboard',
            width: 400,
            height: 450,
        })
    .use(XHR,
        {
            endpoint: window.location.href,
            fieldName: 'picture',
            allowedMetaFields: [
                '_token'
            ]
        })
    .use(ImageEditor,
        {
            target: Dashboard
        });


const tokenFieldId = "_token"
const vehiculeFieldId = "vehicule"
const hiddenTokenElt = document.querySelector(`#${tokenFieldId}`);
const hiddenVehiculeElt = document.querySelector(`#${vehiculeFieldId}`);


uppy.setMeta({
    '_token': hiddenTokenElt.getAttribute("value"),
    'vehicule': hiddenVehiculeElt.getAttribute("value"),
})

uppy.on('complete', (result) => {

    if (result.failed.length === 0) {
        window.location.reload()
    }
});