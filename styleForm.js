import React from 'react';
import styled from 'styled-components';
import logo from './logo.svg';

export const Container = styled.div`
display: flex;
font-size: 16px;
-webkit-font-smoothing: antialiased;
-webkit-text-size-adjust: 100%;
color: #2d2d2d;
font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
* {
  box-sizing: border-box;
}
`;

export const InputBox = styled.div`
width: 20px;
`;


export const HelpmMessage = styled.p`
padding:1em;
`;

export const LoginForm = styled.form`
width: ${props => props.width || '20em'};
padding: 0.25em 1em;
text-align: center;
`;

export const SubButton = styled.div`
`;

export const Contents = styled.div`
display: flex;
flex-direction: column;
align-items: center;
padding-top: 7em;
z-index: 3;
color: white;
min-height: 100vh;
width: 100vw;
position: relative;
word-wrap: break-word;
`;

export const Pattern = styled.div`

z-index: 2;
width: 100%;
position: fixed;
top: 0;
left: 0;
height: 100%;
::before {
  content: " ";
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: 0;
  top: 0;
  left: 0;
  background: -moz-linear-gradient(
    top,
    rgba(0, 0, 0, 0.6) 0%,
    rgba(0, 0, 0, 0) 40%,
    rgba(0, 0, 0, 0) 60%,
    rgba(0, 0, 0, 0) 100%
  );
  background: -webkit-linear-gradient(
    top,
    rgba(0, 0, 0, 0.6) 0%,
    rgba(0, 0, 0, 0) 40%,
    rgba(0, 0, 0, 0) 60%,
    rgba(0, 0, 0, 0) 100%
  );
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.6) 0%,
    rgba(0, 0, 0, 0) 40%,
    rgba(0, 0, 0, 0) 60%,
    rgba(0, 0, 0, 0) 100%
  );
  filter: progid:DXImageTransform.Microsoft.gradient(
      startColorstr='#66000000',
      endColorstr='#00000000',
      GradientType=0
    );
}
`;
export const WelcomeParagraph = styled.h4`
font-size: 26px;
font-weight: normal;
margin: 0.5em 0;
`;

export const AuthPage = ({ children, subtitle }) =>
    <Container>
        <Pattern />
        <Contents>
            
            <WelcomeParagraph>
                {subtitle}
            </WelcomeParagraph>
            {children}
        </Contents>
    </Container>;
