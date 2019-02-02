(* requires at least FPC 2.7.1, compiled using FPC 3.0.4 *)
program PaperScissorsStone;
uses crt, fphttpclient;
type
    StringArray = array of string;
    Hand = 0..2;
const
    WinWidth : integer = 80;
    endpoint : string[80] = 'http://student.tanghin.edu.hk/~S151204/projects/PaperScissorsStone/endpoint?msg=';
var
    MainOptions, HandOptions : StringArray;
    MainMenuChoice, i, win, draw, lose : integer;
    playerChoice, enemyChoice : Hand;
    rm, sec : string;
    id : '0'..'2';
    input : char;

function HandToString(h : Hand) : string;
begin
    case h of
        0: HandToString := 'Paper';
        1: HandToString := 'Scissors';
        2: HandToString := 'Stone';
    end;
end;

function IntToStr(h : Hand) : string;
begin
    Str(h, IntToStr);
end;

procedure WriteLeft(str : string; ln : integer);
begin
    GoToXY(1, ln);
    Write(str);
end;

procedure WriteCenter(str : string; ln : integer);
begin
    GoToXY((WinWidth - Length(str)) div 2, ln);
    Write(str);
end;

procedure WriteRight(str : string; ln : integer);
begin
    GoToXY(WinWidth - Length(str) + 1, ln);
    Write(str);
end;

function Menu(title : string; options : StringArray) : integer;
var
    i : integer;
    input : integer;
    selected : integer;
begin
    ClrScr;
    WriteCenter(title, 2);
    for i := 0 to High(options) do
        WriteCenter(options[i], 4 + i);
    selected := 0;
    TextColor(Yellow);
    WriteCenter('> ' + options[0] + ' <', 4);
    TextColor(LightGray);
    repeat
        input := ord(ReadKey);
        case input of
            0:
            case ord(ReadKey) of
                72:
                begin
                    WriteCenter('  ' + options[selected] + '  ', 4 + selected);
                    selected := (selected + High(options)) mod (High(options) + 1);
                    TextColor(Yellow);
                    WriteCenter('> ' + options[selected] + ' <', 4 + selected);
                    TextColor(LightGray);
                end;
                80:
                begin
                    WriteCenter('  ' + options[selected] + '  ', 4 + selected);
                    selected := (selected + High(options) + 2) mod (High(options) + 1);
                    TextColor(Yellow);
                    WriteCenter('> ' + options[selected] + ' <', 4 + selected);
                    TextColor(LightGray);
                end;
            end;
        end;
    until input = 13;
    for i := 0 to 1 do
    begin
        WriteCenter('> ' + options[selected] + ' <', 4 + selected);
        Delay(50);
        TextColor(Yellow);
        WriteCenter('> ' + options[selected] + ' <', 4 + selected);
        Delay(50);
        TextColor(LightGray);
    end;
    Menu := selected;
end;

begin
    Randomize;
    CursorOff;
    WriteCenter('  _                   __                        __                ', 9);
    WriteCenter(' |_) _. ._   _  ._   (_   _ o  _  _  _  ._ _   (_ _|_  _  ._   _  ', 10);
    WriteCenter(' |  (_| |_) (/_ |    __) (_ | _> _> (_) | _>   __) |_ (_) | | (/_ ', 11);
    WriteCenter('        |                                                         ', 12);
    WriteCenter('created by 4B21', 17);
    SetLength(MainOptions, 3);
    MainOptions[0] := 'Play';
    MainOptions[1] := 'Multiplayer';
    MainOptions[2] := 'Exit';
    SetLength(HandOptions, 3);
    HandOptions[0] := 'Paper';
    HandOptions[1] := 'Scissors';
    HandOptions[2] := 'Stone';
    delay(3000);
    repeat
        win := 0;
        draw := 0;
        lose := 0;
        MainMenuChoice := Menu('Paper Scissors Stone', MainOptions);
        case MainMenuChoice of
            0:
            begin
                for i := 1 to 5 do
                begin
                    playerChoice := Menu('Choose...', HandOptions);
                    enemyChoice := random(3);
                    ClrScr;
                    WriteCenter('Round ' + IntToStr(i), 2);
                    WriteLeft(' Your choice: ' + HandToString(playerChoice), 3);
                    WriteRight('Computer choice: ' + HandToString(enemyChoice) + ' ', 3);
                    case (playerChoice - enemyChoice + 3) mod 3 of
                        0: begin
                            inc(draw);
                            WriteCenter('Draw!', 5);
                        end;
                        1: begin
                            inc(win);
                            WriteCenter('Win!', 5);
                        end;
                        2: begin
                            inc(lose);
                            WriteCenter('Lose!', 5);
                        end;
                    end;
                    WriteCenter('Press any key to continue...', 6);
                    ReadKey;
                end;
                ClrScr;
                WriteLeft(' Win: ' + chr(win + 48), 2);
                WriteCenter('Draw: ' + chr(draw + 48), 2);
                WriteRight('Lose: ' + chr(lose + 48) + ' ', 2);
                WriteCenter('Press any key to return to main menu.', 5);
                ReadKey;
            end;
            1:
            begin
                ClrScr;
                rm := '';
                Str(random(10000), sec);
                for i := 1 to 4 - Length(sec) do
                    sec := '0' + sec;
                WriteCenter('The game will end after 5 rounds (not including draw)', 6);
                WriteCenter('Enter a room code:     ', 5);
                GoToXY(WhereX - 4, 5);
                CursorOn;
                repeat
                    input := ReadKey;
                    case input of
                        '0'..'9':
                        begin
                            Write(input);
                            rm := rm + input;
                        end;
                        #8:
                        begin
                            Write(#8, ' ', #8);
                            rm := copy(rm, 1, Length(rm) - 1);
                        end;
                        #13: ;
                        else
                        begin
                            TextColor(Red);
                            Write(input);
                            TextColor(LightGray);
                            Write(#8);
                        end;
                    end;
                until Length(rm) = 4;
                CursorOff;
                WriteCenter('  Connecting to server  ', 5);
                case TFPCustomHTTPClient.SimpleGet(endpoint + rm + sec) of
                    '0': id := '0';
                    '1': id := '1';
                    'no spectator':
                    begin
                        id := '2';
                        ClrScr;
                        WriteCenter('The room is already full.', 4);
                        WriteCenter('Press any key to return to main menu.', 6);
                        ReadKey;
                    end;
                end;
                WriteCenter('                        ', 5);
                WriteCenter('The game will end after 5 rounds (not including draw)', 6);
                if id = '0' then
                begin
                    WriteCenter('Waiting for a opponent', 4);
                    TFPCustomHTTPClient.SimpleGet(endpoint + rm + sec + '0');
                end;
                if id <> '2' then
                begin
                    WriteCenter('The game will start automatically after 3 seconds.', 4);
                    Delay(3000);
                    i := 1;
                    repeat
                        playerChoice := Menu('Choose...', HandOptions);
                        WriteCenter('Waiting for your opponent...', 8);
                        Val(TFPCustomHTTPClient.SimpleGet(endpoint + rm + sec + id + IntToStr(playerChoice)), enemyChoice);
                        ClrScr;
                        case (playerChoice - enemyChoice + 3) mod 3 of
                            0: begin
                                inc(draw);
                                WriteCenter('Draw!', 6);
                            end;
                            1: begin
                                inc(win);
                                WriteCenter('Win!', 6);
                            end;
                            2: begin
                                inc(lose);
                                WriteCenter('Lose!', 6);
                            end;
                        end;
                        WriteCenter('Round ' + IntToStr(i), 2);
                        WriteCenter(IntToStr(win) + ' vs ' + IntToStr(lose), 3);
                        WriteLeft(' Your choice: ' + HandToString(playerChoice), 4);
                        WriteRight('Opponent''s choice: ' + HandToString(enemyChoice) + ' ', 4);
                        if win + lose <> 5 then
                        begin
                            WriteCenter('The game will continue after 3 seconds.', 7);
                            Delay(3000);
                            inc(i);
                        end
                        else
                        begin
                            WriteCenter('Press any key to continue', 7);
                            ReadKey;
                            ClrScr;
                            WriteCenter('The game has ended.', 2);
                            if win > lose then
                                WriteCenter('You win the match by getting more points than your opponent. (' + IntToStr(win) + ' vs ' + IntToStr(lose) + ')', 3)
                            else
                                WriteCenter('You lose the match. Better luck next time! (' + IntToStr(win) + ' vs ' + IntToStr(lose) + ')', 3);
                            WriteCenter('Press any key to return to main menu.', 5);
                            ReadKey;
                        end;
                    until win + lose >= 5;
                end;
            end;
        end;
    until MainMenuChoice = 2;
end.