import React from 'react';
import PropTypes from 'prop-types';


import{
  Container,InputBox,HelpmMessage,LoginForm,SubButton,
  AuthPage,WelcomeParagraph
} from './styleForm';



class LoginPage extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      username: props.username,
      error: props.error,
      info: props.info,
      password: ''
    };
  }


render = () =>{
  return(

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
  };
}