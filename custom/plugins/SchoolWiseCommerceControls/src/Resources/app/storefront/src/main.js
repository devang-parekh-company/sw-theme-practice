import SchoolCodeAutocomplete from './js/school-code-autocomplete';
import ValidateSchoolKey from './js/validate-school-key';

window.PluginManager.register(
    'ValidateSchoolKey',
    ValidateSchoolKey,
    '[data-school-key]'
);

window.PluginManager.register(
    'SchoolCodeAutocomplete',
    SchoolCodeAutocomplete,
    '[data-school-code-autocomplete]'
);
