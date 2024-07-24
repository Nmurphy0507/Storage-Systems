    // if i add a # sign in front of the word signup it works otherwise it doesn't.
    //const validation = new JustValidate("#signup"); 
    
    const validation = new JustValidate("signup");

        validation
            .addField('#signupUsername', [
                {
                    rule: 'required',
                    errorMessage: 'Username is required'
                },    
            ])
            .addField('#signupEmail', [
                {
                    rule: 'required',
                    errorMessage: 'Email is required'
                },
                {
                    rule: 'email',
                    errorMessage: 'Email is invalid'
                }
            ])
            .addField('#signupPassword', [
                {
                    rule: 'required',
                    errorMessage: 'Password is required'
                },
                {
                    rule: 'password',
                    errorMessage: 'Password must be at least 8 characters, one letter, and one number.'
                }
            ])
            .addField('#signupConfirmPassword', [
                {
                    rule: 'required',
                    errorMessage: 'Confirm Password is required'
                },
                {
                    validator: (value, fields) => {
                        return value === document.getElementById('signupPassword').value;
                    },
                    errorMessage: 'Passwords do not match'
                }
            ]);

            
                





