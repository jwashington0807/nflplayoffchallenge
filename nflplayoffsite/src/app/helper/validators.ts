import { AbstractControl } from '@angular/forms';

export function passwordMatchValidator(passwordControlName: string, matchingControlName: string) {
    return (group: AbstractControl) => {
    
        const control = group.get(passwordControlName);
        const matchingControl = group.get(matchingControlName);
    
        if (!control || !matchingControl) {
            return null;
        }
    
        // return if another validator has already found an error on the matchingControl
        if (matchingControl.errors && !matchingControl.errors['passwordMatchValidator']) {
            return null;
        }

        if(control.value !== matchingControl.value)
        {
            matchingControl.setErrors( { passwordMatchValidator: true });
        }
        else {
            matchingControl.setErrors(null);
        }

        return null;
    }
};
  
export function emailMatchValidator(emailControlName: string, matchingControlName: string) {
    return (group: AbstractControl) => {
    
        const control = group.get(emailControlName);
        const matchingControl = group.get(matchingControlName);
    
        if (!control || !matchingControl) {
            return null;
        }
    
        // return if another validator has already found an error on the matchingControl
        if (matchingControl.errors && !matchingControl.errors['emailMatchValidator']) {
            return null;
        }

        if(control.value !== matchingControl.value)
        {
            matchingControl.setErrors( { emailMatchValidator: true });
        }
        else {
            matchingControl.setErrors(null);
        }

        return null;
    }
};

export function createPasswordStrengthValidator(passwordControlName: string) {
    return (group: AbstractControl) => {

        const control = group.get(passwordControlName);

        if (!control) {
            return null;
        }

        // return if another validator has already found an error on the matchingControl
        if (control.errors && !control.errors['createPasswordStrengthValidator']) {
            return null;
        }

        const hasUpperCase = /[A-Z]+/.test(control.value);

        const hasLowerCase = /[a-z]+/.test(control.value);

        const hasNumeric = /[0-9]+/.test(control.value);

        const passwordValid = hasUpperCase && hasLowerCase && hasNumeric;

        if(!passwordValid) {
            control.setErrors( { createPasswordStrengthValidator: true });
        }
        else {
            control.setErrors(null);
        }

        return null;
    }
}