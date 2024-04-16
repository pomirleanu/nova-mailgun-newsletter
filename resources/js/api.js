export default {
    getLists() {
        return Nova.request()
            .get(`/nova-vendor/pomirleanu/mailgun-newsletter/lists`)
            .then(response => response.data);
    },

    deleteList(address) {
        return Nova.request().delete(`/nova-vendor/pomirleanu/mailgun-newsletter/lists/${address}`, {
            params: { address }
        });
    },

    getTemplates(){
        return Nova.request()
            .get(`/nova-vendor/pomirleanu/mailgun-newsletter/templates`)
            .then(response => response.data);
    },

    deleteTemplate(templateName){
        return Nova.request().delete(`/nova-vendor/pomirleanu/mailgun-newsletter/templates/${templateName}`, {
            params: { templateName }
        });
    }
};
