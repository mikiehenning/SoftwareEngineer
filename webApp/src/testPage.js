import React, { Component } from 'react';
import logo from './logo.svg';
import PropTypes from 'prop-types';
//import styled from 'styled-components';

import {
    Container, InputBox, HelpmMessage, LoginForm, SubButton,
    AuthPage, WelcomeParagraph
} from './styleForm';

import {
    InputField, InputGroup, StackedInputs, SubmitButton
} from './shared';

class TestPage extends Component {
    constructor(props) {
        super(props);
        this.state = {
            username: props.username,
            error: props.error,
            info: props.info,
            password: ''
        };
    }


    render() {
        return (
            <AuthPage subtitle="Sign in to Corstata">
                <StackedInputs>
                    <InputField
                        type="email"
                        name="uname"
                        id="username"
                        value={this.state.email}
                        onInput={this.changeUsername}
                        placeholder="Email address"
                        required
                        autoFocus
                    />
                    <InputGroup>
                        <InputField
                            type="password"
                            name="password"
                            onInput={this.changePassword}
                            placeholder="Password"
                            required
                        />
                        <SubmitButton>
                            <i className="fa fa-sign-in fa-lg" />
                        </SubmitButton>
                    </InputGroup>
                </StackedInputs>
            </AuthPage>
        );
    }; //no semi colon here before
}
export default TestPage;