import axios from 'axios';
import {reactive} from 'vue';

export const rules = reactive({});
export let errors = reactive({});

export function fetchRules(apiEndpoint) {
    Object.keys(errors).forEach(key => {
        errors[key] = '';
    });
    return axios.get(apiEndpoint)
        .then(response => {
            const fetchedRules = response.data.rules || {};
            Object.keys(fetchedRules).forEach((key) => {
                const normalizedKey = key.replace(/\.\*\./g, '.');
                rules[normalizedKey] = fetchedRules[key];
            });
        })
        .catch(error => {
            console.error('Error fetching rules:', error);
        });
}

export function validateField(field, form, conditionField = null, arrayName = null, index = null) {
    const fullField = index !== null ? `${arrayName}.0.${field}` : field;
    const value = index !== null ? form[arrayName][index][field] : form[field];
    const fieldRules = rules[fullField];
    const fieldErrors = [];

    errors[field] = '';

    if (conditionField && rules[field]) {
        const condition = rules[field].find(rule => rule.type === 'required_if');
        if (condition) {
            const [conditionFieldName, expectedValue] = condition.limit.split(',');
            if (form[conditionFieldName] === expectedValue) {
                if (!value || value.trim() === '') {
                    fieldErrors.push(condition.message);
                }
            }
        }
    }

    for (const rule of fieldRules) {
        const {type, limit, message} = rule;

        switch (type) {
            case 'required_if':
                const [dependentFieldName, dependentValue] = limit.split(',');
                if (form[dependentFieldName] === dependentValue && !value) {
                    fieldErrors.push(message);
                }
                break;
            case 'required':
                if (!value || (typeof value === 'string' && value.trim() === '')) {
                    fieldErrors.push(message);
                }
                break;
            case 'string':
                if (typeof value !== 'string') {
                    fieldErrors.push(message);
                }
                break;
            case 'letters':
                if (!/[A-Za-z]/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'numbers':
                if (!/[0-9]/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'symbols':
                if (!/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'uncompromised':
                if (!validateUncompromised(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'confirmed':
                if (form['password'] !== form['password_confirmation']) {
                    fieldErrors.push(message);
                }
                break;
            case 'digits':
                const digitLength = limit || 0;
                if (!new RegExp(`^\\d{${digitLength}}$`).test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'integer':
                if (!Number.isInteger(parseInt(value, 10))) {
                    fieldErrors.push(message);
                }
                break;
            case 'min':
                if (typeof value === 'string' && value.length < limit) {
                    fieldErrors.push(message);
                }
                break;
            case 'max':
                if (typeof value === 'string' && value.length > limit) {
                    fieldErrors.push(message);
                }
                break;
            case 'textOnly':
                if (!/^[A-Za-z\s]+$/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'email' || 'emailValidationRule':
                if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'mobile_number':
                if (!/^\d{10,12}$/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'aadhaarNumber':
                if (!/^\d{12}$/.test(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'dateRule':
                dateRule(value, message, fieldErrors);
                break;
            case 'panCardRule':
                validatePanCard(value, message, fieldErrors);
                break;
            case 'numeric':
                validateNumeric(value, message, fieldErrors);
                break;
            case 'AcceptTextAndNumbersRule':
                AcceptTextAndNumbersRule(value, message, fieldErrors);
                break;
            case 'PastDateOnlyRule':
                pastDateOnlyRule(value, message, fieldErrors);
                break;
            case 'FutureDateOnlyRule':
                futureDateOnlyRule(value, message, fieldErrors);
                break;
            case 'UsernameValidation':
                usernameValidation(value,fieldErrors,form['via'])
                break;
            case 'file':
                if (!value || !(value instanceof File)) {
                    fieldErrors.push(message);
                }
                break;
            case 'mimes':
                if (value && value instanceof File) {
                    const allowedMimes = limit.split(',');
                    const fileMime = value.type;
                    const fileExtension = value.name.split('.').pop().toLowerCase();
                    if (!allowedMimes.includes(fileExtension)) {
                        fieldErrors.push(message);
                    }
                }
                break;
            case 'maxFileSize':
                if (value && value instanceof File && value.size > limit * 1024) {
                    fieldErrors.push(message);
                }
                break;
            case 'date':
                if (!isValidDate(value)) {
                    fieldErrors.push(message);
                }
                break;
            case 'before_or_equal':
                const relatedField = `${field}.${index}.endDate`;
                const endDate = form[relatedField];
                if (new Date(value) > new Date(endDate)) {
                    fieldErrors.push(message);
                }
                break;
            case 'after_or_equal':
                const startDate = form[`${field}.${index}.startDate`];
                if (new Date(value) < new Date(startDate)) {
                    fieldErrors.push(message);
                }
                break;
            default:
                console.warn(`Unsupported validation rule: ${type}`);
                break;
        }

        if (fieldErrors.length > 0) {
            errors[field] = fieldErrors[0];
            return;
        }
    }

    // Update errors if there are any validation failures
    if (fieldErrors.length > 0) {
        errors[fullField] = fieldErrors[0]; // Display the first error for simplicity
    } else {
        errors[fullField] = '';
    }

    console.log(errors)

    function dateRule(value, message, fieldErrors) {
        try {
            const date = new Date(value);
            const today = new Date();

            const minDate = new Date();
            minDate.setFullYear(today.getFullYear() - 100);

            const maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() + 100);

            if (date < minDate || date > maxDate) {
                fieldErrors.push(message);
                return false;
            }

            return true;
        } catch (error) {
            fieldErrors.push(message);
            return false;
        }
    }

    function pastDateOnlyRule(value, message, fieldErrors) {
        try {
            const date = new Date(value);
            const today = new Date();

            const minDate = new Date();
            minDate.setFullYear(today.getFullYear() - 100);

            const maxDate = new Date(today);

            if (date < minDate || date > maxDate) {
                fieldErrors.push(message);
                return false;
            }

            return true;
        } catch (error) {
            fieldErrors.push(message);
            return false;
        }
    }

    function futureDateOnlyRule(value, message, fieldErrors) {
        try {
            const date = new Date(value);
            const today = new Date();

            today.setHours(0, 0, 0, 0);

            const maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() + 1);
            maxDate.setHours(23, 59, 59, 999);

            if (date < today || date > maxDate) {
                fieldErrors.push(message);
                return false;
            }

            return true;
        } catch (error) {
            fieldErrors.push(message);
            return false;
        }
    }

    function validatePanCard(value, message, fieldErrors) {
        if (value === null) {
            return true;
        }

        const panRegex = /^[a-z]{5}[0-9]{4}[a-z]$/i;

        if (!panRegex.test(value)) {
            fieldErrors.push(message);
            return false;
        }

        return true;
    }

    function validateNumeric(value, message, fieldErrors) {
        const numericRegex = /^[0-9]+$/;

        if (!numericRegex.test(value)) {
            fieldErrors.push(message);
            return false;
        }

        return true;
    }

    function AcceptTextAndNumbersRule(value, message, fieldErrors) {
        const alphanumericRegex = /^[a-zA-Z0-9\s]+$/;

        if (!alphanumericRegex.test(value)) {
            fieldErrors.push(message);
            return false;
        }

        return true;
    }

    function validatePhoneNumber(value, message, fieldErrors) {
        const phoneRegex = /^\d{10}$/;

        if (!phoneRegex.test(value)) {
            fieldErrors.push(message);
            return false;
        }

        return true;
    }

    function usernameValidation(value, fieldErrors, via) {
        const emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^\d{10,12}$/;

        if (via === 'email') {
            if (!emailRegex.test(value)) {
                fieldErrors.push('Please enter a valid email address.');
                return false;
            }
        } else if (via === 'phone') {
            if (/[^0-9]/.test(value)) {
                fieldErrors.push('Please enter a valid phone number without letters or special characters.');
                return false;
            } else if (!phoneRegex.test(value)) {
                fieldErrors.push('Please enter a valid phone number with 10 to 12 digits.');
                return false;
            }
        }

        return true;
    }

    function validateUncompromised(value) {
        const compromisedPasswords = [
            'password',
            'password123',
            '12345678',
            'qwerty',
            'abc123',
            'azerty',
            '000000',
            'dragon',
            'zaq12wsx',
            'letmein',
            'princess',
            'sunshine',
            'superman',
            'asdfghjkl',
        ];

        return !compromisedPasswords.includes(value);
    }

    function isValidDate(value) {
        return !isNaN(Date.parse(value));
    }
}

function getValueFromPath(obj, path) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}
export function maxDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

export function minDate() {
    const today = new Date();

    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

export function allowOnlyNumbers(event, allowDot = true) {
    const key = event.key;
    const inputValue = event.target.value;

    if (
        !/[0-9]/.test(key) &&
        key !== 'Backspace' &&
        key !== 'Tab' &&
        !['ArrowLeft', 'ArrowRight'].includes(key) &&
        (!allowDot || key !== '.' || inputValue.includes('.'))
    ) {
        event.preventDefault();  // Stop invalid characters from being typed
    }
}

