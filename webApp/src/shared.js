import React from 'react';
//import { Link } from 'react-router';
import styled from 'styled-components';
//import Logo from 'components/Logo';
//import fullScreenVideo from './corstrata.mp4';

//import pattern from 'assets/pattern_photos.png';
//import 'font-awesome/css/font-awesome.min.css';

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

/*export const Video = () =>
    <div style={{ position: 'fixed', zIndex: 1 }}>
        <video autoPlay loop muted>
            <source src={fullScreenVideo} type="video/mp4" />
        </video>
    </div>;*/

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

export const HelpMessage = styled.p`
  background-color: rgba(0, 0, 0, .1);
  padding: 1em;
`;

export const AuthForm = styled.form`
  width: ${props => props.width || '20em'};
  padding: 0.25em 1em;
  text-align: center;
`;

export const InputField = styled.input`
  flex: 1;
  width: 100%;
  height: 34px;
  font-size: 14px;
  padding: 6px 12px;
  margin-bottom: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
`;

export const SubmitButton = styled.button.attrs({ type: 'submit' }) `
  min-width: 40px;
  height: 34px;
  cursor: pointer;
  background-image: linear-gradient(180deg, #fff 0, #e0e0e0);
  border: none;
  border-radius: 4px;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;

  &:hover {
    background-color: #e0e0e0;
    background-image: unset;

    &:active {
      background-image: linear-gradient(180deg, #e0e0e0 0, #fff);
    }
  }
`;

export const InputGroup = styled.div`
  display: flex;
  width: 100%;
  border-radius: 4px;

  ${InputField} {
    border-radius: inherit;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  ${SubmitButton} {
    border-radius: inherit;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
`;

export const StackedInputs = styled.div`
  > * {
    margin-bottom: 0;
    border-radius: 0;

    :first-child {
      border-top-left-radius: 4px;
      border-top-right-radius: 4px;
    }

    :last-child {
      border-bottom-left-radius: 4px;
      border-bottom-right-radius: 4px;
    }
  }
`;

export const FormLabel = styled.label`
  display: block;
  font-weight: 700;
  text-align: center;
  margin-bottom: 5px;
  margin-top: 10px;
`;

export const ButtonRow = styled.div`
  display: flex;
  margin-top: 2em;
  justify-content: center;

  > * {
    margin: 0 0.5em;
  }
`;

export const FlashMessage = styled(({ rgb, ...rest }) => <div {...rest} />).attrs({
    rgb: props =>
        ({
            error: '250, 117, 90',
            success: '47, 204, 115',
            info: '47, 204, 115'
        }[props.type || 'info'])
}) `
  background-color: ${props => `rgba(${props.rgb}, 0.5)`};
  border: 1px solid ${props => `rgb(${props.rgb})`};
  border-radius: 4px;
  margin: 0.5em 0;
  padding: 1em;
  color: white;
  text-align: center;
  width: 100%;
`;

export const BottomLinks = styled.div`
  display: flex;
  justify-content: space-around;
  margin: 3em auto;
  width: 80%;
  font-weight: 400;
  font-size: 14px;
`;

/*export const BottomLink = styled(({ plain, ...rest }) => <Link {...rest} />) `
  color: ${props => (props.plain ? 'white' : '#337ab7')};
  text-decoration: none;
  cursor: pointer;

  :hover {
    color: #337ab7;
    text-decoration: underline;
  }
`;*/

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
