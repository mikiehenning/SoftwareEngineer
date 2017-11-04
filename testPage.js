import React, { Component } from 'react';
import logo from './logo.svg';
import PropTypes from 'prop-types';
//import styled from 'styled-components';

import {
    Container, InputBox, HelpmMessage, LoginForm, SubButton,
    AuthPage, WelcomeParagraph
} from './styleForm';

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
                <InputBox>test</InputBox>
            </AuthPage>
        );
    }; //no semi colon here before
}
export default TestPage;