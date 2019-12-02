function cleanValue(string) {
    var replacement = [
        ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
        ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    replacement = [
        ['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],
        ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    replacement = [
        ['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],
        ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    replacement = [
        ['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],
        ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    replacement = [
        ['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],
        ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    replacement = [
        ['ñ', 'Ñ', 'ç', 'Ç'],
        ['n', 'N', 'c', 'C',]
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    // Remove weird characters
    replacement = [
        ["\\", "¨", "º", "~",
             "#", "@", "|", "\"",
             "·", "$", "%", "&", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´", "'",
             ">", "< ", "?", ";", "+", '"',
             "\n"],
        ['']
    ];

    // Replace last array
    string = replaceArray(replacement, string);

    return string;
}

function replaceArray(replacement, string) {
    var oldValues = replacement[0];
    var newValues = replacement[1];

    var newValue = '';
    for (var i = 0; i < oldValues.length; i++) {
        // Value to be replaced
        var oldValue = oldValues[i];

        // Get new value if found, default ''
        if (newValues.length > i) {
            newValue = newValues[i];
        };

        // Replace
        string = string.replace(oldValue, newValue);
    };

    return string;
}