import { Base64ImageConverter } from "../../../form-utils/main.js";
import { get_user } from "../../../user-logged/main.js";

export function get_form_data_object(steps = [], images = []) {
    const form = new FormData();
    
    steps.forEach(step => {

        Object.keys(step).forEach(key => {
            form.append(key, step[key]);
        });
    });


    images.forEach(image => {
        try 
        {
            const imageObject = new Base64ImageConverter(image);
            const file = imageObject.getFile();
            
            if (file)
                form.append('photo[]', file);
            else
                return null;
        } 
        catch (conversionError) {
            return null;
        }
    })

    form.append('token', get_user());

    return form;
}