import React, { useState, useEffect, Fragment } from "react";
import {
    Table,
    Thead,
    Tbody,
    Tfoot,
    Tr,
    Th,
    Td,
    TableCaption,
    TableContainer,
    Container,
    Button,
    VStack,
    Box,
    StackDivider,
    Badge,
} from "@chakra-ui/react";

export default function League() {
    const [state, setState] = useState({ league: [], matches: [], championship: [] });
    const [week, setWeek] = useState(1);

    const handleNextWeeek = () => {
        if (week <= 5) {
            let nextWeek = week;
            nextWeek++;
            setWeek(nextWeek);
            loadLeagueTable(nextWeek);
            loadweeklyMatchTable(nextWeek);
            getWeeklStatictics(nextWeek)
        }
    };

    const sum = state?.championship?.reduce((cur, next) => cur + next.value, 0)

    async function loadLeagueTable(week_count = 1) {
        let list = await fetch(`/league-table/${week_count}`);
        let data = await list.json();
        console.log(data);
        setState((previous) => ({ ...previous, league: data }));
    }

    async function handlePlayAllMatcher(){
        for (let index = 1; index <7; index++) {
            await fetch(`/simulate-championship/${index}`);
            await fetch(`/league-table/${index}`);
            
        }
    }

    async function getWeeklStatictics(week_count = 1) {
       if(week_count>=4){
        let list = await fetch(`/simulate-championship/${week_count}`);
        let data = await list.json();
        console.log(data);
        setState((previous) => ({ ...previous, championship: data }));
       }
    }

    async function loadweeklyMatchTable(week_count = 1) {
        let list = await fetch(`/simulate-week/${week_count}`);
        let data = await list.json();
        console.log(data);
        setState((previous) => ({ ...previous, matches: data }));
    }

    useEffect(() => {
        loadLeagueTable();
        loadweeklyMatchTable();
    }, []);
    console.log(state);
    return (
        <Fragment>
            <VStack
                divider={<StackDivider borderColor="gray.200" />}
                spacing={3}
                align="stretch"
            >
                <Box>
                    <Badge>Champions League - #Week-{week}</Badge>
                </Box>
                <Box display={"flex"} gap={5}>
                    <Box>
                        <Badge>League Table</Badge>
                        <TableContainer>
                            <Table variant="striped" colorScheme="teal">
                                <Thead>
                                    <Tr>
                                        <Th>Team</Th>
                                        <Th>Matches Played</Th>
                                        <Th>Draws</Th>
                                        <Th>Losses</Th>
                                        <Th>Wins</Th>
                                        <Th>Goal Difernece</Th>
                                        <Th>Points</Th>
                                    </Tr>
                                </Thead>
                                <Tbody>
                                    {state?.league.map((league) => (
                                        <Tr>
                                            <Td>{league.team.name}</Td>
                                            <Td>{league.matches_played}</Td>
                                            <Td>{league.draws}</Td>
                                            <Td>{league.losses}</Td>
                                            <Td>{league.wins}</Td>
                                            <Td>{league.goal_difference}</Td>
                                            <Td>{league.points}</Td>
                                        </Tr>
                                    ))}
                                </Tbody>
                            </Table>
                        </TableContainer>
                    </Box>
                    <Box>
                        <TableContainer>
                            <Badge>Match results - #Week-{week}</Badge>
                            <Table variant="striped" colorScheme="teal">
                                <Thead>
                                    <Tr>
                                        <Th>H-Team</Th>
                                        <Th>Result</Th>
                                        <Th>A-Team</Th>
                                    </Tr>
                                </Thead>
                                <Tbody>
                                    {state?.matches?.map((matchy) => (
                                        <Tr>
                                            <Td>{matchy.home_team.name}</Td>
                                            <Td>
                                                {matchy.home_score} -{" "}
                                                {matchy.away_score}
                                            </Td>
                                            <Td>{matchy.away_team.name}</Td>
                                        </Tr>
                                    ))}
                                </Tbody>
                            </Table>
                        </TableContainer>
                    </Box>
                </Box>
               {state.championship.length && (
                 <Box>
                 <TableContainer>
                     <Badge colorScheme={"blackAlpha"}>
                         {week} - #Week Predictions of Championship
                     </Badge>
                     <Table variant="striped" colorScheme="gray">
                         <Thead>
                             <Tr>
                                 <Th>Team</Th>

                                 <Th>W-Point</Th>
                             </Tr>
                         </Thead>
                         <Tbody>
                             {state?.championship?.map((matchy) => (
                                 <Tr>
                                     <Td>{matchy.name}</Td>
                                     <Td>{parseInt((matchy.value / sum)* 100)}- %</Td>
                                 </Tr>
                             ))}
                         </Tbody>
                     </Table>
                 </TableContainer>
             </Box>
               )}
                <Box>
                    <div className="table_down_buttons">
                        <Button onClick={() => handlePlayAllMatcher()} colorScheme="teal" size="xs">
                            Play All
                        </Button>
                        <Button
                            disabled={week >= 6}
                            colorScheme="teal"
                            size="xs"
                            onClick={() => handleNextWeeek()}
                        >
                            Next
                        </Button>
                    </div>
                </Box>
            </VStack>
        </Fragment>
    );
}
